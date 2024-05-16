<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\GameUtilService;

use App\Models\User;
use App\Models\WeaponInstance;
use App\Models\WeaponExp;
use App\Models\Weapon;
use App\Models\ItemInstance;
use App\Models\Log;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class LimitBreakController extends Controller
{
    /* 武器限界突破
    /* uid = ユーザーID
    /* wid = 武器ID
    */
    public function __invoke(Request $request)
    {
        $result = 0;
        $errcode = '';
        $response = 0;

        // ユーザー情報
        $userData = User::where('user_id',$request->uid)->first();

        // ユーザー管理ID
        $manage_id = $userData->manage_id;

        // 強化する武器のデータ
        $weapon_id = $request->wid;
        $weaponBase = WeaponInstance::where('manage_id',$manage_id)->where('weapon_id',$weapon_id);
        $weaponData = $weaponBase->first();
        $item_id = 0;

        // アイテムID取得
        switch($weapon_id)
        {
            case config('constants.NORMAL_SWORD_ID'): // 普通の剣
                $item_id = config('constants.NORMAL_SWORD_ITEM_ID');
                 break;
             case config('constants.NORMAL_BOW_ID'): // 普通の弓
                 $item_id = config('constants.NORMAL_BOW_ITEM_ID');
                 break;
             case config('constants.NORMAL_SPEAR_ID'): // 普通の槍
                 $item_id = config('constants.NORMAL_SPEAR_ITEM_ID');
                 break;
             case config('constants.STRONG_BOW_ID'): // 強い弓
                 $item_id = config('constants.STRONG_BOW_ITEM_ID');
                 break;
             case config('constants.VERY_STRONG_SWORD_ID'): // めっちゃ強い剣
                 $item_id = config('constants.VERY_STRONG_SWORD_ITEM_ID');
                 break;
             default:
                 break;
         }
        
        if($item_id > 0)
        {
            $itemDataBase = ItemInstance::where('manage_id',$manage_id)->where('item_id',$item_id);
            $itemData = $itemDataBase->first();

            // エラーチェック
            $limit_max = $weaponData->limit_break_max;
            $limit_break_num = $weaponData->limit_break;
            $has_convex_item = $itemData->item_num;
            if($limit_break_num > $limit_max){$result = -2;}
            if($has_convex_item <= 0){$result = -3;}
        }
        else
        {
            $result = -1;
        }

        // 武器を強化
        DB::transaction(function() use(&$result,$userData,$manage_id,$weaponBase,$weaponData,$itemDataBase,$itemData,$weapon_id,$has_convex_item){
            
            // ログ関連
            $log_category = 0;
            $log_context = '';

            // 凸アイテムを減らす
            $consumptionItemNum = 1;
            $result = $itemDataBase->update([
                'item_num' => $has_convex_item - $consumptionItemNum,
                'used_num' => $itemData->used_num + $consumptionItemNum,
            ]);

            // ログを追加する処理
            $itemData = $itemDataBase->first();
            $log_category = config('constants.ITEM_DATA');
            $log_context = config('constants.USE_ITEM').$consumptionItemNum.'/'.$itemData;
            GameUtilService::logCreate($manage_id,$log_category,$log_context);

            // 限界突破
            $result = $weaponBase->update([
                'limit_break'=>$weaponData->limit_break + 1,
            ]);

            // ログを追加する処理
            $weaponData = $weaponBase->first();
            $log_category = config('constants.WEAPON_DATA');
            $log_context = config('constants.LIMIT_BREAK_WEAPON').$weaponData;
            GameUtilService::logCreate($manage_id,$log_category,$log_context);

            $result = 1;
        });

        switch($result)
        {
            case -1:
                $errcode = config('constants.HASNT_WEAPON');
                $response = [
                    'errcode' => $errcode,
                ];
                break;
            case -2:
                $errcode = config('constants.MAX_LIMIT_BREAK');
                $response = [
                    'errcode' => $errcode,
                ];
                break;
            case -3:
                $errcode = config('constants.NOT_ENOUGH_CONVEX_ITEM');
                $response = [
                    'errcode' => $errcode,
                ];
                break;
            case 0:
                $errcode = config('constants.CANT_LIMIT_BREAK');
                $response = [
                    'errcode' => $errcode,
                ];
                break;
            case 1:
                $response =[
                    'users' => User::where('manage_id',$manage_id)->first(),
                    'weapons' => WeaponInstance::where('manage_id',$manage_id)->get(),
                    'items' => ItemInstance::where('manage_id',$manage_id)->get(),
                ];
                break;
        }

        return json_encode($response);
    }
}

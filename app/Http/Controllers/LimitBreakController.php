<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use App\Models\WeaponInstance;
use App\Models\WeaponExp;
use App\Models\Weapon;
use App\Models\ItemInstance;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class LimitBreakController extends Controller
{
    public function __invoke(Request $request)
    {
        $result = 0;
        // ユーザー情報取得
        $userData = Users::where('user_id',$request->uid)->first();
        // 強化する武器のデータ取得
        $weaponData = WeaponInstance::where('manage_id',$userData->manage_id)->where('weapon_id',$request->wid)->first();

        $weapon_id = $weaponData->weapon_id;
        $normalSwordId = 40002;
        $normalBowId = 40003;
        $normalSpearId = 40004;
        $strongBowId = 40005;
        $veryStrongSwordId = 40006;        
        // TODO:ここはもっと簡略化したい
        switch($weapon_id)
        {
            case 1010001: // 普通の剣
                $itemDataBase = ItemInstance::where('manage_id',$userData->manage_id)->where('item_id',$normalSwordId);
                 break;
             case 1020001: // 普通の弓
                $itemDataBase = ItemInstance::where('manage_id',$userData->manage_id)->where('item_id',$normalBowId);
                 break;
             case 1030001: // 普通の槍
                $itemDataBase = ItemInstance::where('manage_id',$userData->manage_id)->where('item_id',$normalSpearId);
                 break;
             case 2020001: // 強い弓
                $itemDataBase = ItemInstance::where('manage_id',$userData->manage_id)->where('item_id',$strongBowId);
                 break;
             case 3010001: // めっちゃ強い剣
                $itemDataBase = ItemInstance::where('manage_id',$userData->manage_id)->where('item_id',$veryStrongSwordId);
                 break;
        }

        $itemData = $itemDataBase->first();
        
        
        // 武器を強化
        DB::transaction(function() use($userData,$weaponData,$itemDataBase,$itemData,$result,$request){
            $limit_max = $weaponData->limit_break_max;
            $limit_break_num = $weaponData->limit_break;
            $has_convex_item = $itemData->item_num;
            if($weaponData==null){return json_encode("所持していない武器です");}
            if($limit_break_num > $limit_max){return json_encode("限界突破が上限を超えています");}
            if($has_convex_item <= 0){return json_encode("所持凸アイテムが足りません");}
            // 凸アイテムを減らす
            $result = $itemDataBase->update([
                'item_num' => $has_convex_item - 1,
            ]);

            // 限界突破
            $result = WeaponInstance::where('manage_id',$userData->manage_id)->where('weapon_id',$request->wid)->update([
                'limit_break'=>$weaponData->limit_break + 1,
            ]);
        });

        $response =[
            'users'=>Users::where('user_id',$request->uid)->first(),
            'weapons'=>WeaponInstance::where('manage_id',$userData->manage_id)->get(),
            'items'=>$itemData = ItemInstance::where('manage_id',$userData->manage_id)->get(),
        ];
        return json_encode($response);
    }
}

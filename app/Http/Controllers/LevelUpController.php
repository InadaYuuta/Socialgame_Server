<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\WeaponInstance;
use App\Models\WeaponExp;
use App\Models\Weapon;
use App\Models\Log;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class LevelUpController extends Controller
{
    /* 武器強化
    /* uid = ユーザーID
    /* wid = 武器ID
    /* rp = 消費するポイント
    */
    public function __invoke(Request $request)
    {
        $result = 0;
        $errmsg = '';
        $response = 0;

        // ユーザー情報
        $userBase = User::where('user_id',$request->uid);
        $userData = $userBase->first();

        // ユーザー管理ID
        $manage_id = $userData->manage_id;

        // 強化する武器のデータ
        $weaponBase = WeaponInstance::where('manage_id',$manage_id)->where('weapon_id',$request->wid);
        $weaponData = $weaponBase->first();

        // 所持強化ポイント
        $has_reinforce_point = $userData->has_reinforce_point;

        // 消費ポイント
        $consumptionPoint = $request->rp;

        // エラーチェック
        if($weaponData == null){$result = -1;}
        if($weaponData->level >= 50){$result = -2;}
        $has_reinforce_point = $userData->has_reinforce_point;
        if($has_reinforce_point < $consumptionPoint){$result = -3;}

        // 武器を強化
        DB::transaction(function() use (&$result,$userBase,$userData,$manage_id,$weaponBase,$weaponData,$has_reinforce_point,$consumptionPoint){

            // ログ関連
            $log_category = 0;
            $log_context = '';

            // 強化ポイントを減らす
            $result = $userBase->update([
                'has_reinforce_point' => $has_reinforce_point - $consumptionPoint,
            ]);

            // ログを追加する処理
            $log_category = config('constants.USER_DATA');
            $log_context = config('constants.USE_HAS_REINFORCE_POINT').$consumptionPoint.'/'.$userData;
            Log::create([
                'manage_id' => $manage_id,
                'log_category' => $log_category,
                'log_context' => $log_context,
            ]);

            // レベルアップ
            $result = $weaponBase->update([
                'level'=>$weaponData->level + 1,
            ]);

            // ログを追加する処理
            $weaponData = $weaponBase->first();
            $log_category = config('constants.WEAPON_DATA');
            $log_context = config('constants.LEVEL_UP_WEAPON').$weaponData;
            Log::create([
                'manage_id' => $manage_id,
                'log_category' => $log_category,
                'log_context' => $log_context,
            ]);
            $result = 1;
        });

        switch($result)
        {
            case -1:
                $errmsg = config('constants.HASNT_WEAPON');
                $response = [
                    'errmsg' => $errmsg,
                ];
                break;
            case -2:
                $errmsg = config('constants.MAX_LEVEL');
                $response = [
                    'errmsg' => $errmsg,
                ];
                break;
            case -3:
                $errmsg = config('constants.NOT_ENOUGH_REINFORCEPOINT');
                $response = [
                    'errmsg' => $errmsg,
                ];
                break;
            case 0:
                $errmsg = config('constants.CANT_LEVEL_UP');
                $response = [
                    'errmsg' => $errmsg,
                ];
                break;
            case 1:
                $response =[
                    'users' => $userBase->first(),
                    'weapons' => WeaponInstance::where('manage_id',$manage_id)->get(),
                ];
                break;
        }

        return json_encode($response);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\GameUtilService;

use App\Models\User;
use App\Models\WeaponInstance;
use App\Models\Weapon;
use App\Models\EvolutionWeapon;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EvolutionController extends Controller
{
    /* 武器進化 
    /* uid = ユーザーID
    /* wid = 武器ID
    */
    public function __invoke(Request $request)
    {
        $result = 0;
        $errcode = '';
        $response = [];

        // --- Auth処理(ログイン確認)-----------------------------------------
        // ユーザーがログインしていなかったらリダイレクト
        if (!Auth::hasUser()) {
            $response = [
                'errcode' => config('constants.ERRCODE_LOGIN_USER_NOT_FOUND'),
            ];
            return json_encode($response);
        }

        $authUserData = Auth::user();

         // ユーザー情報取得
         $userData = User::where('user_id',$request->uid)->first();
       
        // ユーザー管理ID
        $manage_id = $userData->manage_id;

        // ログインしているユーザーが自分と違ったらリダイレクト
        if ($manage_id != $authUserData->manage_id) {
            $response = [
                'errcode' => config('constants.ERRCODE_LOGIN_SESSION'),
            ];
            return json_encode($response);
        }
        // -----------------------------------------------------------------

        // 進化元の武器のデータ
        $weaponBase = WeaponInstance::where('manage_id',$manage_id)->where('weapon_id',$request->wid);
        $weaponData = $weaponBase->first();
        $rarity_id = $weaponData->rarity_id;

        // 武器マスタデータ
        $masterWeaponData = Weapon::where('weapon_id',$weaponData->weapon_id)->first();

        // 進化したあとの武器の情報
        $evolutionData = EvolutionWeapon::where('evolution_weapon_id',$masterWeaponData->evolution_weapon_id)->first();

        // 所持強化ポイント
        $has_reinforce_point = $userData->has_reinforce_point;

        // 消費ポイント
        $consumptionPoint = $request->rp;

        // TODO: 後日メソッド化
        // 消費ポイント計算
        switch($rarity_id)
        {
            case 1: // COMON
                $consumptionPoint = 5000;
                break;
            case 2: // RARE
                $consumptionPoint = 10000;
                break;
            case 3: // SRARE
                $consumptionPoint = 15000;
                break;
            default:
                $errcode = config('constants.ERRCODE_NOT_EVOLUTION_WEAPON');
                $response = [
                    'errcode' => $errcode,
                ];
                return json_encode($response);
            break;
        }

        // エラーチェック
        $check = WeaponInstance::where('manage_id',$manage_id)->where('weapon_id',$evolutionData->evolution_weapon_id)->first();
        if($check != null)
        {
            $errcode = config('constants.ERRCODE_NOT_EVOLUTION_WEAPON');
                $response = [
                    'errcode' => $errcode,
                ];
            return json_encode($response);
        }
        if($weaponData==null)
        {
            $errcode = config('constants.ERRCODE_HAS_NOT_WEAPON');
                $response = [
                    'errcode' => $errcode,
                ];
            return json_encode($response);
        }
        if($weaponData->level <= 49)
        {
            $errcode = config('constants.ERRCODE_NOT_ENOUGH_LEVEL');
                $response = [
                    'errcode' => $errcode,
                ];
            return json_encode($response);
        }
        if($has_reinforce_point < $consumptionPoint)
        {
            $errcode = config('constants.ERRCODE_NOT_ENOUGH_REINFORCE_POINT');
                $response = [
                    'errcode' => $errcode,
                ];
            return json_encode($response);
        }

        // 武器を進化
        DB::transaction(function() use (&$result,$userData,$manage_id,$weaponBase,$weaponData,$consumptionPoint,$evolutionData,$has_reinforce_point){
            
            // ログ関連
            $log_category = 0;
            $log_context = '';
            
            // 強化ポイントを減らす
            $result = User::where('manage_id',$manage_id)->update([
                'has_reinforce_point' => $has_reinforce_point - $consumptionPoint,
            ]);

            // ログを追加する処理
            $log_category = config('constants.USER_DATA');
            $log_context = config('constants.USE_HAS_REINFORCE_POINT').$consumptionPoint.'/'.$userData;
            GameUtilService::logCreate($manage_id,$log_category,$log_context);

            // 進化
            $result = $weaponBase->update([
                'weapon_id' => $evolutionData->evolution_weapon_id,
                'rarity_id' => $evolutionData->rarity_id,
                'evolution' => 1,
            ]);

            // ログを追加する処理
            $log_category = config('constants.WEAPON_DATA');
            $log_context = config('constants.EVOLUTION_WEAPON').$weaponData;
            GameUtilService::logCreate($manage_id,$log_category,$log_context);

            $result = 1;
        });

        switch($result)
        {
            case 0:
                $errcode = config('constants.ERRCODE_CANT_EVOLUTION');
                $response = [
                    'errcode' => $errcode,
                ];
                break;
            case 1:
                $response =[
                    'users' => User::where('manage_id',$manage_id)->first(),
                    'weapons' => WeaponInstance::where('manage_id',$manage_id)->get(),
                ];
                break;
        }
        
        return json_encode($response);
    }
}

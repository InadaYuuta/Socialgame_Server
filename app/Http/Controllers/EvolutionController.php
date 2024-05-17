<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\GameUtilService;

use App\Models\User;
use App\Models\WeaponInstance;
use App\Models\Weapon;
use App\Models\EvolutionWeapon;

use Illuminate\Support\Facades\DB;

class EvolutionController extends Controller
{
    /* 武器進化 
    /* uid = ユーザーID
    /* wid = 武器ID
    /* rp = 消費するポイント
    */
    public function __invoke(Request $request)
    {
        $result = 0;
        $errcode = '';
        $response = [];

        // ユーザー情報
        $userData = User::where('user_id',$request->uid)->first();

        // ユーザー管理ID
        $manage_id = $userData->manage_id;

        // 進化元の武器のデータ
        $weaponBase = WeaponInstance::where('manage_id',$manage_id)->where('weapon_id',$request->wid);
        $weaponData = $weaponBase->first();

        // 武器マスタデータ
        $masterWeaponData = Weapon::where('weapon_id',$weaponData->weapon_id)->first();

        // 進化したあとの武器の情報
        $evolutionData = EvolutionWeapon::where('evolution_weapon_id',$masterWeaponData->evolution_weapon_id)->first();

        // 所持強化ポイント
        $has_reinforce_point = $userData->has_reinforce_point;

        // エラーチェック
        $check = WeaponInstance::where('manage_id',$manage_id)->where('weapon_id',$evolutionData->evolution_weapon_id)->first();
        if($check != null){$result = -1;}
        if($weaponData==null){$result = -2;}
        if($weaponData->level <= 49){$result = -3;}
        if($has_reinforce_point < $request->rp){$result = -4;}

        // 武器を進化
        DB::transaction(function() use ($userData,$manage_id,$weaponBase,$weaponData,$masterWeaponData,$evolutionData,$result,$request,$has_reinforce_point){
            
            // ログ関連
            $log_category = 0;
            $log_context = '';

            // 消費ポイント
            $consumptionPoint = $request->rp;
            // 強化ポイントを減らす
            $result = User::where('manage_id',$manage_id)->update([
                'has_reinforce_point' => $has_reinforce_point - $consumptionPoint,
            ]);

            // ログを追加する処理
            $log_category = config('constants.USER_DATA');
            $log_context = config('constants.USE_HAS_REINFORCE_POINT').$consumptionPoint.'/'.$userData;
            GameUtilService::logCreate($manage_id,$log_category,$log_context);

            // レベルアップ
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
            case -1:
                $errcode = config('constants.HAS_WEAPON');
                $response = [
                    'errcode' => $errcode,
                ];
                break;
            case -2:
                $errcode = config('constants.HASNT_WEAPON');
                $response = [
                    'errcode' => $errcode,
                ];
                break;
            case -3:
                $errcode = config('constants.NOT_ENOUGH_LEVEL');
                $response = [
                    'errcode' => $errcode,
                ];
                break;
            case -4:
                $errcode = config('constants.NOT_ENOUGH_REINFORCEPOINT');
                $response = [
                    'errcode' => $errcode,
                ];
                break;
            case 0:
                $errcode = config('constants.CANT_EVOLUTION');
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

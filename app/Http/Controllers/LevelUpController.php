<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\GameUtilService;
use App\Libs\WeaponService;

use App\Models\User;
use App\Models\WeaponInstance;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LevelUpController extends Controller
{
    /* 武器強化
    /* uid = ユーザーID
    /* wid = 武器ID
    */
    public function __invoke(Request $request)
    {
        $result = 0;
        $errcode = '';
        $response = 0;

        // ユーザー情報
        $userBase = User::where('user_id',$request->uid);
        // ユーザー情報取得
        $userData = $userBase->first();

      //  Auth::login($userData); // TODO: これは仮修正、本来ならログインが継続してこの下に入るはずだけど、なぜか継続されないので一旦ここでログイン
        // --- Auth処理(ログイン確認)-----------------------------------------
        // ユーザーがログインしていなかったらリダイレクト
        if (!Auth::hasUser()) {
            $response = [
                'errcode' => config('constants.ERRCODE_LOGIN_USER_NOT_FOUND'),
            ];
            return json_encode($response);
        }

        $authUserData = Auth::user();
       
        // ユーザー管理ID
        $manage_id = $userData->manage_id;

        // ログインしているユーザーが自分と違ったらリダイレクト
        //if ($manage_id != $authUserData->getAuthIdentifier()) {
        if ($manage_id != $authUserData->manage_id) {
            $response = [
                'errcode' => config('constants.ERRCODE_LOGIN_SESSION'),
            ];
            return json_encode($response);
        }
        // -----------------------------------------------------------------

        // 強化する武器のデータ
        $weaponBase = WeaponInstance::where('manage_id',$manage_id)->where('weapon_id',$request->wid);
        $weaponData = $weaponBase->first();

        // 所持強化ポイント
        $has_reinforce_point = $userData->has_reinforce_point;

        // 消費ポイント
        $consumptionPoint = WeaponService::needReinforcePoint($weaponData);

        // エラーチェック
        // 武器を所持していなかったらリダイレクト
        if($weaponData == null)
        {
            $errcode = config('constants.ERRCODE_HAS_NOT_WEAPON');
            $response = [
                'errcode' => $errcode,
            ];
            return json_encode($response);
        }
        // レベル上限に達していたらリダイレクト
        if($weaponData->level >= 50)
        {
            $errcode = config('constants.ERRCODE_MAX_LEVEL');
            $response = [
                'errcode' => $errcode,
            ];
            return json_encode($response);
        }
        // 所持強化ポイントが足りなかったらリダイレクト
        if($has_reinforce_point < $consumptionPoint)
        {
            $errcode = config('constants.ERRCODE_NOT_ENOUGH_REINFORCE_POINT');
            $response = [
                'errcode' => $errcode,
            ];
            return json_encode($response);
        }

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
            GameUtilService::logCreate($manage_id,$log_category,$log_context);

            // レベルアップ
            $result = $weaponBase->update([
                'level'=>$weaponData->level + 1,
            ]);

            // ログを追加する処理
            $weaponData = $weaponBase->first();
            $log_category = config('constants.WEAPON_DATA');
            $log_context = config('constants.LEVEL_UP_WEAPON').$weaponData;
            GameUtilService::logCreate($manage_id,$log_category,$log_context);

            $result = 1;
        });

        switch($result)
        {
            case 0:
                $errcode = config('constants.ERRCODE_CANT_LEVEL_UP');
                $response = [
                    'errcode' => $errcode,
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

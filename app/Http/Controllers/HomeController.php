<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\GameUtilService;
use App\Libs\ErrorUtilService;

use App\Models\User;
use App\Models\UserWallet;
use App\Models\WeaponInstance;
use App\Models\ItemInstance;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /* ホームに入った時に現在の情報を更新
    /* uid = ユーザーID
    */
    public function __invoke(Request $request)
    {
        $result = 0;
        $errcode = '';
        $response = 0;

        // ユーザー情報取得
        $userData = User::where('user_id',$request->uid)->first();

        Auth::login($userData); // TODO: これは仮修正、本来ならログインが継続してこの下に入るはずだけど、なぜか継続されないので一旦ここでログイン
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

         DB::transaction(function() use(&$result,$userData,$manage_id)
        {
           // ログ関連
           $log_category = 0;
           $log_context = '';

          $lastStamina = $userData->last_stamina;
          $maxStamina = $userData->max_stamina;
          $updated = $userData->stamina_updated;
          
          // スタミナ回復
          if($lastStamina < $maxStamina)
          {
            $recoveryStamina = GameUtilService::getCurrentStamina($lastStamina, $maxStamina, $updated);
            if($recoveryStamina >= $maxStamina)
            {
                $currentStamina = $maxStamina;
            }
            else
            {
                $currentStamina = $lastStamina + $recoveryStamina;
                if($currentStamina >= $maxStamina)
                {
                    $currentStamina = $maxStamina;
                }
            }

            $result = User::where('manage_id',$manage_id)->update([
              'last_stamina' => $currentStamina,
            ]); 

            // ログを追加する処理
            $userData = User::where('manage_id',$manage_id)->first();
            $log_category = config('constants.USER_DATA');
            $log_context = config('constants.LOGIN_USER').$userData;
            GameUtilService::logCreate($manage_id,$log_category,$log_context);
          }
          $result = 1;
        });

        switch($result)
        {
            case 0:
                $errcode = config('constants.CANT_UPDATE_HOME');
                $response = [
                    'errcode' => $errcode,
                ];
                break;
            case 1:
                $weaponCheck = WeaponInstance::where('manage_id',$manage_id)->get();
                if($weaponCheck <= 0){$weaponCheck = 0;} // 中身が無かったら0で返す
                $itemCheck = ItemInstance::where('manage_id',$manage_id)->get();
                if($itemCheck <= 0){$itemCheck = 0;}
                $response =[
                    'user' => User::where('manage_id',$manage_id)->first(),
                    'wallet'=> UserWallet::where('manage_id',$manage_id)->first(),
                    'weapons' => $weaponCheck,
                    'items' => $itemCheck,
                    // TODO: 他にホームに戻った時に取得したい情報があればここに追記
                ];
                break;
        }

        return json_encode($response);
    }
}

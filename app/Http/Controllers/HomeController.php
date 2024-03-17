<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\GameUtilService;

use App\Models\User;
use App\Models\UserWallet;
use App\Models\WeaponInstance;
use App\Models\ItemInstance;
use App\Models\Log;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Session\Session;

class HomeController extends Controller
{
    /* ホームに入った時に現在の情報を更新
    /* uid = ユーザーID
    */
    public function __invoke(Request $request)
    {
        $result = 0;
        $errmsg = '';
        $response = 0;

        // if(!Auth::hasUser())
        // {
        //     $result = -3;
        //     dd($result);
        // }

        // Authから情報取得
        //$authUserData = Auth::user();

        // ユーザー情報取得
        $userData = User::where('user_id',$request->uid)->first();

        // ユーザー管理ID
        $manage_id = $userData->manage_id;

        // クライアントのデータとAuthのデータを照合
        // if ($manage_id != $authUserData->getAuthIdentifier())
        // {
        //     $result = -4;
        // }

         DB::transaction(function() use(&$result,$userData,$manage_id)
        {
           // ログ関連
           $log_category = 0;
           $log_context = '';

          $lastStamina = $userData->last_stamina;
          $maxStamina = $userData->max_stamina;
          $updated = $userData->stamina_updated;
          
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
            case -3:
                $errmsg = config('constants.LOGIN_USER_NOT_FOUND');
                $response = [
                    'errmsg' => $errmsg,
                ];
            case -4:
                $errmsg = config('constants.USER_IS_NOT_LOGGED_IN');
                $response = [
                    'errmsg' => $errmsg,
                ];
            case 0:
                $errmsg = config('constants.CANT_UPDATE_HOME');
                $response = [
                    'errmsg' => $errmsg,
                ];
                break;
            case 1:
                $response =[
                    'user' => User::where('manage_id',$manage_id)->first(),
                    'wallet'=> UserWallet::where('manage_id',$manage_id)->first(),
                    'weapons' => WeaponInstance::where('manage_id',$manage_id)->get(),
                    'items' => ItemInstance::where('manage_id',$manage_id)->get(),
                    // TODO: 他にホームに戻った時に取得したい情報があればここに追記
                ];
                break;
        }

        return json_encode($response);
    }
}

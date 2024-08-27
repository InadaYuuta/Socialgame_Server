<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\GameUtilService;

use App\Models\User;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TestStaminaConsumptionController extends Controller
{
    /* スタミナ消費 TODO: 今後クエストを実装するときに中身を変更する
    /* uid = ユーザーID
    */
    public function __invoke(REQUEST $request)
    {
        $result = 0;
        $errcode = '';
        $response = 0;

        // --- Auth処理(ログイン確認)-----------------------------------------
        // ユーザーがログインしていなかったらリダイレクト
        if (!Auth::hasUser()) {
            $response = [
                'errcode' => config('constants.ERRCODE_LOGIN_USER_NOT_FOUND'),
            ];
            return json_encode($response);
        }

        $authUserData = Auth::user();

        // ユーザー情報
        $userBase = User::where('user_id',$request->uid);

        // ユーザー情報取得
        $userData = $userBase->first();
       
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

        DB::transaction(function() use (&$result,$userData,$manage_id){

            // ログ関連
            $log_category = 0;
            $log_context = '';

            $currentStamina = $userData->last_stamina;
            $consumptionStamina = 5; // 消費するスタミナ、今回は一旦仮で5
            $resultStamina = $currentStamina -$consumptionStamina; // 消費後のスタミナ
            if($resultStamina >= 0)
            {
                $result = User::where('manage_id',$manage_id)->update([
                    'last_stamina' => $resultStamina,
                    'stamina_updated' =>Carbon::now()->format('Y-m-d H:i:s'),
                ]);
            }

            // ログを追加する処理(スタミナ更新)
            $log_category = config('constants.USER_DATA');
            $log_context = config('constants.CONSUMPTION_STAMINA').$consumptionStamina.'/'.$userData;
            GameUtilService::logCreate($manage_id,$log_category,$log_context);
            $result = 1;
        });
        switch($result)
        {
            case 0:
                $errcode = config('constants.ERRCODE_CANT_STAMINA_CONSUMPTION');
                $response = $errcode;
                break;
            case 1:
                $response = [
                    'users'=>User::where('manage_id',$manage_id)->first(),
                ];
                break;
        }

        return json_encode($response);
    }
}

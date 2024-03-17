<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Log;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TestStaminaConsumptionController extends Controller
{
    /* スタミナ消費 TODO: 今後クエストを実装するときに中身を変更する
    /* uid = ユーザーID
    */
    public function __invoke(REQUEST $request)
    {
        $result = 0;
        $errmsg = '';
        $response = 0;

        // ユーザー情報
        $userData = User::where('user_id',$request->uid)->first();

        // ユーザー管理ID
        $manage_id = $userData->manage_id;

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
            Log::create([
                'manage_id' => $manage_id,
                'log_category' => $log_category,
                'log_context' => $log_context,
            ]);
            $result = 1;
        });
        switch($result)
        {
            case 0:
                $errmsg = config('constants.CANT_STAMINA_CONSUMTION');
                $response = $errmsg;
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

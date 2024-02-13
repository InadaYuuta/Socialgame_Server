<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TestStaminaConsumptionController extends Controller
{
    public function __invoke(REQUEST $request)
    {
        $result = 0;
        // ユーザー情報取得
        $userData = Users::where('user_id',$request->uid)->first();
        DB::transaction(function() use($userData,$request){
            $currentStamina = $userData->last_stamina;
            $consumptionStamina = 5; // 消費するスタミナ、今回は一旦仮で5
            $resultStamina = $currentStamina -$consumptionStamina; // 消費後のスタミナ
            if($resultStamina >= 0)
            {
                $result = Users::where('user_id',$userData->user_id)->update([
                    'last_stamina' => $resultStamina,
                    'stamina_updated' =>Carbon::now()->format('Y-m-d H:i:s'),
                ]);
            }
        });

        $response = [
            'users'=>Users::where('user_id',$request->uid)->first(),
        ];
        return json_encode($response);
    }
}

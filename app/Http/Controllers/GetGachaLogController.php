<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\GachaLog;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class GetGachaLogController extends Controller
{
    /* ガチャのログ取得
    /* uid = ユーザーID
    */
    public function __invoke(Request $request)
    {
        $result = 0;
        $errcode = '';
        $response = [];

        // ユーザー情報取得
        $userData = User::where('user_id',$request->uid)->first();
        $manage_id = $userData->manage_id;

        DB::transaction(function() use (&$result,&$response,$manage_id){
            $response = [
                'gacha_log'=>GachaLog::where('manage_id',$manage_id)->get(),
            ]; 
            $result = 1;
        });

        switch($result)
        {
            case 0:
                $errcode = config('constants.CANT_GET_GACHA_LOG');
                $response = [
                    'errcode' => $errcode,
                ];
                break;
            case 1:
                break;
        }

        return json_encode($response);
    }
}

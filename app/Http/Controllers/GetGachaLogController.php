<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\GachaLog;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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

        DB::transaction(function() use (&$result,&$response,$manage_id){
            $response = [
                'gacha_log'=>GachaLog::where('manage_id',$manage_id)->get(),
            ]; 
            $result = 1;
        });

        if($result == 0)
        {
            $errcode = config('constants.ERRCODE_CANT_GET_GACHA_LOG');
            $response = [
                'errcode' => $errcode,
            ];
        }

        return json_encode($response);
    }
}

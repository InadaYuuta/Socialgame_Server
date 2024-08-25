<?php

namespace App\Http\Controllers;

use App\Libs\MissionUtilService;
use App\Libs\PresentBoxUtilService;
use App\Models\MissionInstance;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\PresentBoxInstance;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GetMissionDataController extends Controller
{
    /*ミッションデータの受取
     * $uid = ユーザーID
     */
    public function __invoke(Request $request)
    {
        $result = 0;
        $errcode = '';
        $response = [];
       // ユーザー情報
       $userBase = User::where('user_id',$request->uid);
       // ユーザー情報取得
       $userData = $userBase->first();

       //Auth::login($userData); // TODO: これは仮修正、本来ならログインが継続してこの下に入るはずだけど、なぜか継続されないので一旦ここでログイン
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

       DB::transaction(function() use ($result,$manage_id){
         // もしも最初にミッションデータを受け取る際に必要なミッションデータが無ければ作成
         MissionUtilService::firstCreateMission($result,$manage_id);
       });

       $missionsData = MissionInstance::where('manage_id',$manage_id)->get();
       if($missionsData != null)
       {
        $result = 1;
       }
       else
       {
        $result = 0;
       }

       switch($result)
        {
            case 0:
                $errcode = config('constants.ERRCODE_CANT_GET_MISSION');
                $response = [
                    'errcode' => $errcode,
                ];
                break;
            case 1:
                $response = [
                    'missions' => MissionInstance::where('manage_id',$manage_id)->get(),
                ];
                break;
        }

        return json_encode($response);
    }
}

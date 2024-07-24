<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\GameUtilService;
use App\Libs\MissionUtilService;
use App\Models\User;
use App\Models\UserWallet;
use App\Models\ItemInstance;
use App\Models\Mission;
use App\Models\MissionInstance;
use App\Models\Log;
use App\Models\PresentBoxInstance;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReceiveMissionController extends Controller
{
    
    /* ミッション報酬受け取り
    /* uid = ユーザーID
    /* mid = ミッションID
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

        // 受け取るミッションの情報
        $missionBase = MissionInstance::where('manage_id',$manage_id)->where('mission_id',$request->mid);
        $missionData = $missionBase->first();
        
        // エラーチェック
        $check = $missionData->achieved;
        // 達成済みかどうか確認
        if($check <= 0)
        {
            $errcode = config('constants.ERRCODE_MISSION_NOT_ACCOMPLISHED');
            $response = $errcode;
            return json_encode($response);
        } 
        // 受取済みかどうかを確認
        if($missionData->receipt > 0)
        {
            $errcode = config('constants.ERRCODE_MISSION_ALREADY_RECEIVE');
            $response = $errcode;
            return json_encode($response);
        } 

        // ミッション受取
        DB::transaction(function() use (&$result,$userData,$manage_id,$missionData,$missionBase){
            $missionMasterData = Mission::where('mission_id',$missionData->mission_id)->first();
            $reward_category = $missionMasterData->reward_category; // カテゴリー
            $missionRewardNum = Str::after($missionMasterData->mission_reward,'/'); // 個数

            $result = MissionUtilService::receiveMove($userData,$missionBase,$reward_category,$missionRewardNum);
        });

        switch($result)
        {
            case 0:
                $errcode = config('constants.ERRCODE_CANT_RECEIVE_MISSION');
                $response = $errcode;
                break;
            case 1:
                $response = [
                    'users'=>User::where('manage_id',$manage_id)->first(),
                    'wallets'=>UserWallet::where('manage_id',$manage_id)->first(),
                    'items'=>ItemInstance::where('manage_id',$manage_id)->get(),
                ];
                break;
            case 2:
                $can_receipt_present_data = PresentBoxInstance::where('manage_id',$manage_id)->get();
                $response = [
                    'users'=>User::where('manage_id',$manage_id)->first(),
                    'wallets'=>UserWallet::where('manage_id',$manage_id)->first(),
                    'items'=>ItemInstance::where('manage_id',$manage_id)->get(),
                    'present_box' => $can_receipt_present_data,
                ];
                break;
        }
        

        return json_encode($response);
    }
}

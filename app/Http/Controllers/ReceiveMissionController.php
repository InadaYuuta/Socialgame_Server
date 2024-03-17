<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\UserWallet;
use App\Models\ItemInstance;
use App\Models\Mission;
use App\Models\MissionCategory;
use App\Models\MissionInstance;
use App\Models\Log;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ReceiveMissionController extends Controller
{
    
    /* ミッション報酬受け取り
    /* uid = ユーザーID
    /* mid = ミッションID
    */
    public function __invoke(Request $request)
    {
        $result = 0;
        $errmsg = '';
        $response = 0;
        // ユーザー情報
        $userData = User::where('user_id',$request->uid)->first();

        // ユーザー管理ID
        $manage_id = $userData->manage_id;

        // 受け取るミッションの情報
        $missionBase = MissionInstance::where('manage_id',$manage_id)->where('mission_id',$request->mid);
        $missionData = $missionBase->first();
        
        // エラーチェック
        $check = $missionData->achieved;
        if($check <= 0){ $result = -2;} // 達成済みかどうか確認
        if($missionData->receipt > 0){$result = -1;} // 受取済みかどうかを確認
        DB::transaction(function() use (&$result,$userData,$manage_id,$missionData,$missionBase){
            if($result = 0){return;}
            if($missionData->receipt > 0){return;}

            // ログ関連
            $log_category = 0;
            $log_context = '';

            $isItem = 0; // アイテムかどうか
            $missionMasterData = Mission::where('mission_id',$missionData->mission_id)->first();
            $reward_category = $missionMasterData->reward_category; // カテゴリー
            $missionRewardNum = Str::after($missionMasterData->mission_reward,'/'); // 個数
            switch($reward_category)
            {
                case 1: // 通貨
                    $walletBase = UserWallet::where('manage_id',$manage_id);
                    $walletData = $walletBase->first();
                    $result = $walletBase-> update([
                        'free_amount' => $walletData->free_amount + $missionRewardNum,
                    ]);

                    // ログを追加する処理
                    $log_category = config('constants.CURRENCY_DATA'); // 通貨情報更新
                    $log_context = config('constants.GET_CURRENCY').$missionRewardNum.'/'.'walletData/'.$walletData;
                    Log::create([
                        'manage_id' => $manage_id,
                        'log_category' => $log_category,
                        'log_context' => $log_context,
                    ]);
                    break;
                case 2: // スタミナ回復アイテム
                    $item_id = config('constants.STAMINA_RECOVERY_ITEM_ID');
                    $isItem = 1;
                    break;
                case 3: // 強化ポイント
                    $result = User::where('manage_id',$manage_id)->update([
                        'has_reinforce_point' => $userData->has_reinforce_point + $missionRewardNum,
                    ]);

                    // ログを追加する処理
                    $log_category = config('constants.USER_DATA');
                    $log_context = config('constants.GET_HAS_REINFORCE_POINT').$missionRewardNum.'/'.$userData;
                    Log::create([
                        'manage_id' => $manage_id,
                        'log_category' => $log_category,
                        'log_context' => $log_context,
                    ]);
                   break;
                case 4: // 交換アイテム
                    $item_id = config('constants.EXCHANGE_ITEM_ID');
                    $isItem = 1;
                    break;
                case 5: // 凸アイテム
                    $item_id = config('constants.CONVEX_ITEM_ID');
                    $isItem = 1;
                    break;
                case 6: // 武器
                    // TODO:武器も取得するようにするなら追記
                    break;
            }

            if($isItem > 0)
            {
                $itemBase = ItemInstance::where('manage_id',$manage_id)->where('item_id',$item_id); // アイテムの取得用
                $itemData = $itemBase->first();
                $result = $itemBase->update([
                    'item_num' => $itemData->item_num + $missionRewardNum,
                ]);

                // ログを追加する処理
                $itemData = $itemBase->first();
                $log_category = config('constants.ITEM_DATA');
                $log_context = config('constants.GET_ITEM').$missionRewardNum.'/'.$itemData;
                Log::create([
                    'manage_id' => $manage_id,
                    'log_category' => $log_category,
                    'log_context' => $log_context,
                ]);
            }
             // ここで受け取り完了処理
             $result = $missionBase->update([
               'receipt' => 1,
            ]);

            // ログを追加する処理(ミッション受け取り更新)
            $missionData = $missionBase->first(); // ミッションデータ
            $log_category = config('constants.MISSION_DATA');
            $log_context = config('constants.RECEIPT_MISSION').$missionData;
            Log::create([
                'manage_id' => $manage_id,
                'log_category' => $log_category,
                'log_context' => $log_context,
            ]);

            $result = 1;
        });

        switch($result)
        {
            case -1:
                $errmsg = config('constants.MISSION_ALREADY_RECEIVE');
                $response = $errmsg;
                break;
            case -2:
                $errmsg = config('constants.MISSION_NOT_ACCOMPLISHED');
                $response = $errmsg;
                break;
            case 0:
                $errmsg = config('constants.CANT_RECEIVE_MISSION');
                $response = $errmsg;
                break;
            case 1:
                $response = [
                    'users'=>User::where('manage_id',$manage_id)->first(),
                    'wallets'=>UserWallet::where('manage_id',$manage_id)->first(),
                    'items'=>ItemInstance::where('manage_id',$manage_id)->get(),
                ];
                break;
        }
        

        return json_encode($response);
    }
}

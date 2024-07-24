<?php
namespace App\Libs;

use App\Models\ItemInstance;
use App\Models\Log;
use App\Models\Mission;
use App\Models\MissionInstance;
use App\Models\User;
use App\Models\UserWallet;
use Illuminate\Support\Facades\Auth;

class MissionUtilService
{
    /*初めてミッションを作成するときに、指定のミッションを一括で生成
     * $result=呼び出すコードのresult $manage_id = 呼び出すユーザーのマネージID
     */
    public static function firstCreateMission(&$result,$manage_id)
    {
        // 最初だけ一括で生成(1010001~1050001まで)
        $check = MissionInstance::where('manage_id',$manage_id)->where('mission_id',1010001)->first();
        if($check == null)
        {
            // 最初だけ一括で生成(1010001~1050001まで)
            $first_list = [
                [
                    'mission_id' => 1010001,
                ],
                [
                    'mission_id' => 1020001,
                ],
                [
                    'mission_id' => 1030001,
                ],
                [
                    'mission_id' => 1040001,
                ],
                [
                    'mission_id' => 1050001,
                ],
            ];

            foreach($first_list as $data)
            {
                $check = MissionInstance::where('manage_id',$manage_id)->where('mission_id',$data['mission_id'])->first();
                if($check == null)
                {
                    $instanceData = Mission::where('mission_id',$data['mission_id'])->first();
                    $result = MissionInstance::create([
                        'manage_id'=>$manage_id,
                        'mission_id'=>$instanceData->mission_id,
                        'term' => $instanceData->period_end,
                    ]);
                }
                else
                {
                    continue;
                }
            }
        }
    }

   

    /* ミッションを達成したときに次のミッションがあればそれを作成する
     * $manage_id = プレイヤーのmanage_id
     * $achieveMissionData = 達成したミッションのデータ
     */
    public static function createNextMission($manage_id,$achieveMissionData)
    {
       $next_mission_id = $achieveMissionData->next_mission_id;
       // 次のミッションが存在するかどうかチェック、あれば次のミッションを作成
       if($next_mission_id == null)
    {
        return config('constants.ERRCODE_NEXT_MISSION_DOES_NOT_EXITS'); /* TODO:ここはエラーコードにする*/
    }

       $createMissionData = Mission::where('mission_id',$next_mission_id)->first();

       // 新しいミッションを作成する
       $missionInstanceData = MissionInstance::create([
        'manage_id' => $manage_id,
        'mission_id' => $next_mission_id,
        'term' => $createMissionData->period_end,
       ]);
    }



    /**
    * 所持数に報酬を足して、上限を超えていないかを確認し受け取れるかどうか
    */
    public static function checkPossessionNum($userData,$rewardCategory,$rewardNum):bool
    {
        $result = true;
        $manage_id = $userData->manage_id;

        $possessionNum = 0; // 現在の報酬受け取り前の所持数
        $possessionMax = 0; // 所持できる最大数

        $item_max = 99999;

        switch($rewardCategory)
        {
            case 1: // 通貨
                $walletData = UserWallet::where('manage_id',$manage_id)->first();
                $freeAmount = $walletData->free_amount;
                $paidAmount = $walletData->paid_amount;

                $possessionNum = $freeAmount + $paidAmount + $rewardNum;
                $possessionMax = $walletData->max_amount;
                break;
            case 2: // スタミナ回復アイテム
                $item_id = config('constants.STAMINA_RECOVERY_ITEM_ID');
                $itemData = ItemInstance::where('manage_id',$manage_id)->where('item_id',$item_id)->first();
                $possessionNum = $itemData->item_num + $rewardNum;
                // TODO: ここは所持上限を追加した際に追記する、一旦上限を適当に決めて進む
                $possessionMax = $item_max;
                break;
            case 3: // 強化ポイント
                $possessionNum = $userData->has_reinforce_point + $rewardNum;
                $possessionMax = $item_max; // TODO: ここは所持上限を作成するか、もしくは表示上は99999みたいにして、内部はもっとある形式にするのか考える
                break;
            case 4: // 交換アイテム
                $item_id = config('constants.EXCHANGE_ITEM_ID');
                $itemData = ItemInstance::where('manage_id',$manage_id)->where('item_id',$item_id)->first();
                $possessionNum = $itemData->item_num + $rewardNum;
                // TODO: ここは所持上限を追加した際に追記する、一旦上限を適当に決めて進む
                $possessionMax = $item_max;
                break;
            case 5: // 凸アイテム
                $item_id = config('constants.CONVEX_ITEM_ID');
                $itemData = ItemInstance::where('manage_id',$manage_id)->where('item_id',$item_id)->first();
                $possessionNum = $itemData->item_num + $rewardNum;
                // TODO: ここは所持上限を追加した際に追記する、一旦上限を適当に決めて進む
                $possessionMax = $item_max;
                break;
            case 6: // 武器
                // TODO:武器も取得するようにするなら追記
                break;
        }

        // 所持上限を超えていたらfalseを返す
        if($possessionNum >= $possessionMax)
        {
            $result = false;
            return $result;
        }
        else{
            $result = true;
            return $result;
        }
    }


    /**
     * ミッション報酬受取の際にその報酬の所持数を確認し、最大値に達していないかどうかを確認する
     * 最大値に達していたら、プレゼントとして生成する
     * そうでなければ受け取る
     */
    public static function receiveReward($userData,$missionBase,$rewardCategory,$rewardNum)
    {
        $manage_id = $userData->manage_id;

        $isItem = 0; // アイテムかどうか

        switch($rewardCategory)
        {
            case 1: // 通貨
                $walletBase = UserWallet::where('manage_id',$manage_id);
                $walletData = $walletBase->first();
                $result = $walletBase-> update([
                    'free_amount' => $walletData->free_amount + $rewardNum,
                ]);

                // ログを追加する処理
                $log_category = config('constants.CURRENCY_DATA'); // 通貨情報更新
                $log_context = config('constants.GET_CURRENCY').$rewardNum.'/'.'walletData/'.$walletData;
                GameUtilService::logCreate($manage_id,$log_category,$log_context);
                break;
            case 2: // スタミナ回復アイテム
                $item_id = config('constants.STAMINA_RECOVERY_ITEM_ID');
                $isItem = 1;
                break;
            case 3: // 強化ポイント
                $result = User::where('manage_id',$manage_id)->update([
                    'has_reinforce_point' => $userData->has_reinforce_point + $rewardNum,
                ]);

                // ログを追加する処理
                $log_category = config('constants.USER_DATA');
                $log_context = config('constants.GET_HAS_REINFORCE_POINT').$rewardNum.'/'.$userData;
                GameUtilService::logCreate($manage_id,$log_category,$log_context);
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
                    'item_num' => $itemData->item_num + $rewardNum,
                ]);

                // ログを追加する処理
                $itemData = $itemBase->first();
                $log_category = config('constants.ITEM_DATA');
                $log_context = config('constants.GET_ITEM').$rewardNum.'/'.$itemData;
                GameUtilService::logCreate($manage_id,$log_category,$log_context);
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
    }

    /**
     * ミッションの受取処理
     * * $userData = ユーザーのデータ
     * * $missionBase = ミッションのもと
     * * $rewardCategory = 受け取る報酬のカテゴリ
     * * $rewardNum = 受け取る報酬の量
     */
    public static function receiveMove($userData,$missionBase,$rewardCategory,$rewardNum):int
    {
        $isReceive = MissionUtilService::checkPossessionNum($userData,$rewardCategory,$rewardNum);
        // 受け取れる状態かを確認して、受け取れれば受取、ダメならプレゼントとして作成
        if($isReceive == true)
        {
            MissionUtilService::receiveReward($userData,$missionBase,$rewardCategory,$rewardNum);
            return 1;
        }
        else
        {
            PresentBoxUtilService::CreatePresent($userData->manage_id,$rewardCategory,$rewardNum);
            return 2;
        }
    }
}
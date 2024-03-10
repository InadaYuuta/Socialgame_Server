<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use App\Models\UserWallet;
use App\Models\ItemInstance;
use App\Models\Mission;
use App\Models\MissionCategory;
use App\Models\MissionInstance;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ReceiveMissionController extends Controller
{
    // uid = ユーザーID
    // mid = ミッションID
    public function __invoke(Request $request)
    {
        $result = 0;
        $errmsg = '';
        $response = 0;

        // ユーザー情報
        $userData = Users::where('user_id',$request->uid)->first();

        // ユーザー管理ID
        $manage_id = $userData->manage_id;

        // 受け取るミッションの情報
        $missionBase = MissionInstance::where('manage_id',$manage_id)->where('mission_id',$request->mid);
        $missionData = $missionBase->first();
        
        $check = $missionData->achieved;
        if($check <= 0){ $result = -2;} // 達成済みかどうか確認
        if($missionData->receipt > 0){$result = -1;} // 受取済みかどうかを確認

        DB::transaction(function() use(&$result,$userData,$manage_id,$missionData,$missionBase){
            if($result <= 0){return;}

            $isItem = 0; // アイテムかどうか
            $reward_category = $missionData->reward_category; // カテゴリー
            $prezent_box_reward_num = Str::after($missionData->prezent_box_reward,'/'); // 個数

            switch($reward_category)
            {
                case 1: // 通貨
                    $walletData = UserWallet::where('manage_id',$manage_id)->first();
                    $result = UserWallet::where('manage_id',$manage_id)-> update([
                        'free_amount' => $walletData->free_amount + $prezent_box_reward_num,
                    ]);
                    break;
                case 2: // スタミナ回復アイテム
                    $item_id = 10001;
                    $isItem = 1;
                    break;
                case 3: // 強化ポイント
                    $result = Users::where('manage_id',$manage_id)->update([
                        'has_reinforce_point' => $userData->has_reinforce_point + $prezent_box_reward_num,
                    ]);
                   break;
                case 4: // 交換アイテム
                    $item_id = 30001;
                    $isItem = 1;
                    break;
                case 5: // 凸アイテム
                    $item_id = 40001;
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
                    'item_num' => $itemData->item_num + $prezent_box_reward_num,
                ]);
            }
             // ここで受け取り完了処理
             $result = $missionBase->update([
               'receipt' => 1,
            ]);
            $result = 1;
        });

        switch($result)
        {
            case -1:
                $errmsg = '既に受け取ったミッションです';
                $response = $errmsg;
                break;
            case -2:
                $errmsg = '達成していないミッションです';
                $response = $errmsg;
                break;
            case 0:
                $errmsg = '正常に処理が行われませんでした';
                $response = 0;
                break;
            case 1:
                $response = [
                    'users'=>Users::where('manage_id',$manage_id)->first(),
                    'wallets'=>UserWallet::where('manage_id',$manage_id)->first(),
                    'items'=>ItemInstance::where('manage_id',$manage_id)->get(),
                ];
                break;
        }
        return json_encode($response);
    }
}

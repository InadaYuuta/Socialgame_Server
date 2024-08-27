<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\GameUtilService;

use App\Models\User;
use App\Models\UserWallet;
use App\Models\ItemInstance;
use App\Models\PresentBoxInstance;
use App\Models\Log;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReceivePresentController extends Controller
{
    /* プレゼント受け取り
    /* uid = ユーザーID
    /* pid = プレゼントID
    */
    public function __invoke(Request $request)
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

       // プレゼントIDが存在しなかったらエラー
       if($request->pid == null)
       {
           $errcode = config('constants.ERRCODE_PRESENT_ID_DOES_NOT_EXIST');
           $response = $errcode;
           return json_encode($response);
       }
       
        // 受け取るプレゼントの情報
        $presentBase = PresentBoxInstance::where('manage_id',$manage_id)->where('present_id',$request->pid);
        $presentData = $presentBase->first();
        
        // プレゼントが存在しなかったらエラー
       if($presentData == null)
       {
           $errcode = config('constants.ERRCODE_PRESENT_DOES_NOT_EXIST');
           $response = $errcode;
           return json_encode($response);
       }
        // 受取済みならエラー
        if($presentData->receipt > 0)
        {
            $errcode = config('constants.ERRCODE_PRESENT_ALREADY_RECEIVE');
            $response = $errcode;
            return json_encode($response);
        }

        DB::transaction(function() use(&$result,$userData,$manage_id,$presentData,$presentBase){
            if($result < 0){return;}

            // ログ関連
            $log_category = 0;
            $log_context = '';

            $isItem = 0; // アイテムかどうか
            $reward_category = $presentData->reward_category; // カテゴリー
            $present_box_reward_num = Str::after($presentData->present_box_reward,'/'); // 個数

            switch($reward_category)
            {
                case 1: // 通貨
                    $walletData = UserWallet::where('manage_id',$manage_id)->first();
                    $result = UserWallet::where('manage_id',$manage_id)-> update([
                        'free_amount' => $walletData->free_amount + $present_box_reward_num,
                    ]);
                    // ログを追加する処理
                    $log_category = config('constants.CURRENCY_DATA'); // 通貨情報更新
                    $log_context = config('constants.GET_CURRENCY').$present_box_reward_num.'/'.'walletData/'.$walletData;
                    GameUtilService::logCreate($manage_id,$log_category,$log_context);
                    break;
                case 2: // スタミナ回復アイテム
                    $item_id = config('constants.STAMINA_RECOVERY_ITEM_ID');
                    $isItem = 1;
                    break;
                case 3: // 強化ポイント
                    $result = User::where('manage_id',$manage_id)->update([
                        'has_reinforce_point' => $userData->has_reinforce_point + $present_box_reward_num,
                    ]);

                    // ログを追加する処理
                    $log_category = config('constants.USER_DATA');
                    $log_context = config('constants.GET_HAS_REINFORCE_POINT').$present_box_reward_num.'/'.$userData;
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
                    'item_num' => $itemData->item_num + $present_box_reward_num,
                ]);

                // ログを追加する処理
                $itemData = $itemBase->first();
                $log_category = config('constants.ITEM_DATA');
                $log_context = config('constants.GET_ITEM').$present_box_reward_num.'/'.$itemData;
                GameUtilService::logCreate($manage_id,$log_category,$log_context);
            }

            // ここで受け取り完了処理
            $result = $presentBase->update([
               'receipt' => 1,
               'receipt_date' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);

            // ログを追加する処理
            $log_category = config('constants.PRESENT_BOX_DATA');
            $log_context = config('constants.RECEIPT_PRESENT_BOX').$presentData;
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
                $errcode = config('constants.ERRCODE_CAN_NOT_RECEIVE_PRESENT');
                $response = $errcode;
                break;
            case 1:
                $response = [
                    'users'=>User::where('manage_id',$manage_id)->first(),
                    'wallets'=>UserWallet::where('manage_id',$manage_id)->first(),
                    'items'=>ItemInstance::where('manage_id',$manage_id)->get(),
                    'presents'=>PresentBoxInstance::where('manage_id',$manage_id)->get(),
                ];
                break;
        }
        return json_encode($response);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\GameUtilService;

use App\Models\User;
use App\Models\UserWallet;
use App\Models\ItemInstance;
use App\Models\Log;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class StaminaRecoveryController extends Controller
{
    /* スタミナ回復
    /* uid = ユーザーID
    /* remode = 回復方法
    */
    public function __invoke(Request $request)
    {
        $result = 0;
        $errcode = '';
        $response = 0;

        // ユーザー情報取得
        $userData = User::where('user_id',$request->uid)->first();

        // ユーザー管理ID
        $manage_id = $userData->manage_id;

        // ウォレット情報取得
        $walletBase = UserWallet::where('manage_id',$manage_id);
        $walletData = $walletBase->first();

        // 回復方法を取得
        $recoveryMode = $request->remode;

        DB::transaction(function() use (&$result,$userData,$manage_id,$walletBase,$walletData,$recoveryMode)
        {

            // ログ関連
            $log_category = 0;
            $log_context = '';

            // スタミナ回復処理、現在のスタミナが最大スタミナを超えていないときスタミナを上限まで回復
            if($userData->last_stamina < $userData->max_stamina)
            {
                $result = User::where('user_id',$userData->user_id)->update([
                    'last_stamina' => $userData->max_stamina,
                ]);

                // ログを追加する処理(スタミナ更新)
                $log_category = config('constants.USER_DATA');
                $log_context = config('constants.STAMINA_RECOVERY').$userData;
                GameUtilService::logCreate($manage_id,$log_category,$log_context);

                $consumptionCurrency = 5; // 消費する通貨
                $consumptionItem = 1; // 消費するアイテム
                switch($recoveryMode)
                {
                    case "currency":
                        // 無料分の通貨から消費、無料分が無かったら有償分を消費
                        if($walletData->free_amount > 0)
                        {
                            $result = $walletBase->update([
                                'free_amount' => $walletData->free_amount - $consumptionCurrency,
                            ]);
                        }
                        else
                        {
                            $result = $walletBase->update([
                                'paid_amount' => $walletData->paid_amount - $consumptionCurrency,
                            ]);
                        }

                        // ログを追加する処理(ウォレット更新)
                        $walletData = UserWallet::where('manage_id',$manage_id)->first();
                        $log_category = config('constants.CURRENCY_DATA');
                        $log_context = config('constants.USE_CURRENCY').$consumptionCurrency.'/'.$walletData;
                        GameUtilService::logCreate($manage_id,$log_category,$log_context);
                        break;
                    case "item":
                        $itemBase = ItemInstance::where('manage_id',$manage_id)->where('item_id',config('constants.STAMINA_RECOVERY_ITEM_ID'));
                        $itemData = $itemBase->first();
                        if($itemData->item_num > 0)
                        {
                            $result = $itemBase->update([
                                'item_num' => $itemData->item_num - $consumptionItem,
                                'used_num' => $itemData->used_num + $consumptionItem,
                            ]);
                        }

                        // ログを追加する処理
                        $itemData = $itemBase->first();
                        $log_category = config('constants.ITEM_DATA');
                        $log_context = config('constants.USE_ITEM').$consumptionItem.'/'.$itemData;
                        GameUtilService::logCreate($manage_id,$log_category,$log_context);
                        break;
                    default:
                        break;
                }
                $result = 1;
            }
            else{$result = -1;}
        });
        
        switch($result)
        {
            case -1:
                $errcode = config('constants.CANT_STAMINA_ANY_MORE_STAMINA');
                $response = $errcode;
                break;
            case 0:
                $errcode = config('constants.CANT_STAMINA_RECOVERY');
                $response = $errcode;
                break;
            case 1:
                $response = [
                    'users' => User::where('user_id',$request->uid)->first(),
                    'wallets' => UserWallet::where('manage_id',$manage_id)->first(),
                    'items' => ItemInstance::where('manage_id',$manage_id)->get(),
                ];
                break;
        }

        return json_encode($response);
    }
}

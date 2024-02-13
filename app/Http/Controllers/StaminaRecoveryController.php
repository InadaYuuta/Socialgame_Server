<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use App\Models\UserWallet;
use App\Models\ItemInstance;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class StaminaRecoveryController extends Controller
{
    public function __invoke(Request $request)
    {
        $result = 0;
        // ユーザー情報取得
        $userData = Users::where('user_id',$request->uid)->first();
        // ウォレット情報取得
        $walletData = UserWallet::where('manage_id',$userData->manage_id)->first();
        // 回復方法を取得
        $recoveryMode = $request->remode;
        DB::transaction(function() use($userData,$walletData,$recoveryMode)
        {
            // スタミナ回復処理、現在のスタミナが最大スタミナを超えていないときスタミナを上限まで回復
            if($userData->last_stamina < $userData->max_stamina)
            {
                $result = Users::where('user_id',$userData->user_id)->update([
                    'last_stamina' => $userData->max_stamina,
                ]);

                switch($recoveryMode)
                {
                    case "currency":
                        // 無料分の通貨から消費、無料分が無かったら有償分を消費
                        if($walletData->free_amount > 0)
                        {
                            $result = UserWallet::where('manage_id',$userData->manage_id)->update([
                                'free_amount' => $walletData->free_amount - 5,
                            ]);
                        }
                        else
                        {
                            $result = UserWallet::where('manage_id',$userData->manage_id)->update([
                                'paid_amount' => $walletData->paid_amount - 5,
                            ]);
                        }
                        break;
                    case "item":
                        $has_stamina_recovery_item = ItemInstance::where('manage_id',$userData->manage_id)->where('item_id',10001)->first();
                        if($has_stamina_recovery_item->item_num > 0)
                        {
                            $result = ItemInstance::where('manage_id',$userData->manage_id)->where('item_id',10001)->update([
                                'item_num' => $has_stamina_recovery_item->item_num - 1,
                                'used_num' => $has_stamina_recovery_item->used_num + 1,
                            ]);
                        }
                        break;
                    default:
                        break;
                }
            }
        });
        $response = [
            'users' => Users::where('user_id',$request->uid)->first(),
            'wallets' => UserWallet::where('manage_id',$userData->manage_id)->first(),
            'items' => ItemInstance::where('manage_id',$userData->manage_id)->get(),
        ];
        return json_encode($response);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use App\Models\user_wallets; // TODO: Walletsモデルに名前を変更する
use App\Models\Items;
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
        $walletData = user_wallets::where('manage_id',$userData->manage_id)->first();
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
                            $result = user_wallets::where('manage_id',$userData->manage_id)->update([
                                'free_amount' => $walletData->free_amount - 5,
                            ]);
                        }
                        else
                        {
                            $result = user_wallets::where('manage_id',$userData->manage_id)->update([
                                'paid_amount' => $walletData->paid_amount - 5,
                            ]);
                        }
                        break;
                    case "item":
                        // TODO: スタミナアイテムを消費してスタミナを回復する処理を書く
                        break;
                    default:
                        break;
                }
            }
        });
        $response = [
            'users' => Users::where('user_id',$request->uid)->first(),
            'wallets' => user_wallets::where('manage_id',$userData->manage_id)->first(),
            // TODO: アイテムの結果を返す
        ];
        return json_encode($response);
    }
}

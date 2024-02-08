<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use App\Models\user_wallets; // TODO: Walletsモデルに名前を変更する
use App\Models\PaymentShop;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BuyCurrencyController extends Controller
{
    public function __invoke(Request $request)
    {
        $result = 0;
        // ユーザー情報取得
        $userData = Users::where('user_id',$request->uid)->first();
        // 商品情報取得
        $paymentData = PaymentShop::where('product_id',$request->pid)->first();

        // 指定された商品分通貨を増やす処理
        DB::transaction(function() use($userData,$paymentData,&$result){
            $walletsData = user_wallets::where('manage_id',$userData->manage_id)->first();
            $result = user_wallets::where('manage_id',$userData->manage_id)->update([
                'free_amount' => $walletsData->free_amount + $paymentData->bonus_currency,
                'paid_amount' => $walletsData->paid_amount + $paymentData->paid_currency,
            ]);
        });
        $response = [
           'wallets' => user_wallets::where('manage_id',$userData->manage_id)->first(),
        ];
        return json_encode($response);
    }
}

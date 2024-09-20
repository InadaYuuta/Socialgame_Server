<?php

namespace App\Http\Controllers;

use App\Models\PaymentShop;
use App\Models\User;
use App\Models\UserWallet;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function __invoke()
    {
        return TestController::test_check();
    }

    public static function test_check(){
        $result = 0;
        $errcode = -1;
        $response = [];

        // ユーザー情報取得
        //$userData = User::where('user_id','01J68TDP1C5GCN40347FGZCWEE')->first();
        $userData = User::where('user_id','01HNZ1RCSETXM2PKHVM37HXHM5')->first();
       
        // ユーザー管理ID
        $manage_id = $userData->manage_id;

        // 商品情報取得
        $paymentData = PaymentShop::where('product_id',10001)->first();

        $walletBase = UserWallet::where('manage_id',$manage_id);

        // 指定された商品分通貨を増やす処理
        DB::transaction(function() use (&$result,$paymentData,$walletBase){
            $walletsData = $walletBase->first();
            $bonus_currency = $paymentData->bonus_currency;
            $paid_currency = $paymentData->paid_currency;
            $result = $walletBase->update([
                'free_amount' => $walletsData->free_amount + $bonus_currency,
                'paid_amount' => $walletsData->paid_amount + $paid_currency,
            ]);
            $result = 1;
        });

        switch($result)
        {
            case 0:
                $errcode = config('constants.ERRCODE_CANT_BUY_CURRENCY');
                $response = [
                    'errcode' => $errcode,
                ];
                break;
            case 1:
                $response = [
                    'wallets' => UserWallet::where('manage_id',$userData->manage_id)->first(),
                 ];
                break;
        }
       
        return json_encode($response);
    }
}
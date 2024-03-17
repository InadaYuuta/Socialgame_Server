<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\GameUtilService;

use App\Models\User;
use App\Models\UserWallet;
use App\Models\PaymentShop;
use App\Models\Log;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BuyCurrencyController extends Controller
{
    /* 通貨購入 
    /* uid = ユーザーID
    /* pid = 商品ID
    */
    public function __invoke(Request $request)
    {
        $result = 0;
        $errmsg = '';
        $response = [];

        // ユーザー情報取得
        $userData = User::where('user_id',$request->uid)->first();

        // ユーザー管理ID
        $manage_id = $userData->manage_id;

        // 商品情報取得
        $paymentData = PaymentShop::where('product_id',$request->pid)->first();

        $walletBase = UserWallet::where('manage_id',$manage_id);

        // 指定された商品分通貨を増やす処理
        DB::transaction(function() use (&$result,$manage_id,$paymentData,$walletBase){
            $walletsData = $walletBase->first();
            $bonus_currency = $paymentData->bonus_currency;
            $paid_currency = $paymentData->paid_currency;
            $result = $walletBase->update([
                'free_amount' => $walletsData->free_amount + $bonus_currency,
                'paid_amount' => $walletsData->paid_amount + $paid_currency,
            ]);

            // ログを追加する処理
            $paymentData = PaymentShop::where('product_id',$paymentData->product_id)->first();
            $log_category = config('constants.CURRENCY_DATA'); // 通貨情報更新
            $log_context = config('constants.BUY_CURRENCY').'bonus_currency/'.$bonus_currency.'/'.'paid_currency/'.$paid_currency.'/'.'walletData/'.$paymentData;
            GameUtilService::logCreate($manage_id,$log_category,$log_context);

            $result = 1;
        });

        switch($result)
        {
            case -3:
                $errmsg = config('constants.LOGIN_USER_NOT_FOUND');
                $response = [
                    'errmsg' => $errmsg,
                ];
            case -4:
                $errmsg = config('constants.USER_IS_NOT_LOGGED_IN');
                $response = [
                    'errmsg' => $errmsg,
                ];
            case 0:
                $errmsg = config('constants.CANT_BUYCURRENCY');
                $response = [
                    'errmsg' => $errmsg,
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

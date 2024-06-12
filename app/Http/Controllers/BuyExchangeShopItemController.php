<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\GameUtilService;

use App\Models\User;
use App\Models\UserWallet;
use App\Models\ItemInstance;
use App\Models\ExchangeItemShop;

use Illuminate\Support\Facades\DB;

class BuyExchangeShopItemController extends Controller
{
    /* 交換アイテム購入 
    /* uid  = ユーザーID
    /* epid = 商品ID
    */
    public function __invoke(Request $request)
    {
        $result = 0;
        $errcode = '';
        $response = [];

        // TODO: 今後Authの処理が安定したら

        // ユーザー情報
        $userData = User::where('user_id',$request->uid)->first();

        // ユーザー管理ID
        $manage_id = $userData->manage_id;

        // 交換アイテム情報のもと
        $exchangeItemBase = ItemInstance::where('manage_id',$manage_id)->where('item_id',30001);

        // 交換アイテムデータ
        $exchangeItemData = $exchangeItemBase->first(); 

        // アイテムの所持数
        $item_num = $exchangeItemData->item_num;
        if($item_num <= 10){$result = -1;}

        // 商品情報取得
        $exchangeShopData = ExchangeItemShop::where('exchange_product_id',$request->epid)->first();

        // 指定された商品分通貨やアイテムを増やす処理
        DB::transaction(function() use (&$result,$userData,$manage_id,$exchangeItemBase,$exchangeItemData,$item_num,$exchangeShopData){

            // 交換したアイテムの数
            $exchange_item_amount = $exchangeShopData->exchange_item_amount; 

            $currency = 10001;
            $staminaRecoveryItem = 20001;
            $reinforcePoint = 30001;

            // ログ関連
            $log_category = 0;
            $log_context = ' ';

            // 交換アイテムを10消費
            $consumption =  10;
            $result = $exchangeItemBase->update([
                'item_num' => $item_num - $consumption,
                'used_num' => $exchangeItemData->used_num + 10,
            ]);

            // ログを追加する処理 ------REVIEW: ログごとにこの処理を回しているけどコードが冗長になってしまうので一回にまとめたい
            $log_category = config('constants.ITEM_DATA');
            $log_context = config('constants.USE_ITEM').$consumption.'/'.'itemData/'.$exchangeItemData;
            GameUtilService::logCreate($manage_id,$log_category,$log_context);

            // 交換した分だけ各自を追加
            switch($exchangeShopData->exchange_product_id)
            {
                case $currency: // 通貨更新
                        $walletBase = UserWallet::where('manage_id',$manage_id);
                        $walletsData = $walletBase->first();
                        $result = $walletBase->update([
                        'free_amount' => $walletsData->free_amount + $exchange_item_amount,
                    ]);

                    $walletsData = $walletBase->first();
                    $log_category = config('constants.CURRENCY_DATA');
                    $log_context = config('constants.GET_CURRENCY').$exchange_item_amount.'/'.'walletData/'.$walletsData;
                    break;
                case $staminaRecoveryItem: // スタミナアイテム更新
                        $staminaItemBase = ItemInstance::where('manage_id',$manage_id)->where('item_id',10001);
                        $staminaItemData = $staminaItemBase->first();
                        $result = $staminaItemBase->update([
                        'item_num' => $staminaItemData->item_num + $exchange_item_amount,
                    ]);

                    $staminaItemData = $staminaItemBase->first();
                    $log_category = config('constants.ITEM_DATA');
                    $log_context = config('constants.GET_ITEM').$exchange_item_amount.'/'.'itemData/'.$staminaItemData;
                    break;
                case $reinforcePoint: // 強化ポイント更新
                        $result = User::where('manage_id',$manage_id)->update([
                        'has_reinforce_point' => $userData->has_reinforce_point + $exchange_item_amount,
                    ]);

                    $userData = User::where('manage_id',$manage_id)->first();
                    $log_category = config('constants.USER_DATA');
                    $log_context = config('constants.GET_HAS_REINFORCE_POINT').$exchange_item_amount.'/'.'userData/'.$userData;
                    break;
                default:
                break;
            } 

            // ログを追加する処理 ------
            GameUtilService::logCreate($manage_id,$log_category,$log_context);

            // -----
            $result = 1;
        });

        switch($result)
        {
            case -1:
                $errcode = config('constants.NOT_ENOUGH_EXCHANGE_ITEM');
                $response = [
                    'errcode' => $errcode,
                ];
                break;
            case 0:
                $errcode = config('constants.CANT_EXCHANGE_ITEM');
                $response = [
                    'errcode' => $errcode,
                ];
                break;
            case 1:
                $response = [
                    'users' => User::where('manage_id',$manage_id)->first(),
                    'wallets' => UserWallet::where('manage_id',$manage_id)->first(),
                    'items' => ItemInstance::where('manage_id',$manage_id)->get(),
                ];
                break;
        }
        
        return json_encode($response);
    }
}

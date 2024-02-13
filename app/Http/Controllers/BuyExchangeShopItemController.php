<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use App\Models\UserWallet;
use App\Models\ItemInstance;
use App\Models\ExchangeItemShop;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BuyExchangeShopItemController extends Controller
{
    public function __invoke(Request $request)
    {
        $result = 0;
        // ユーザー情報取得
        $userData = Users::where('user_id',$request->uid)->first();
        // 商品情報取得
        $exchangeShopData = ExchangeItemShop::where('exchange_product_id',$request->epid)->first();

        // 指定された商品分通貨やアイテムを増やす処理
        DB::transaction(function() use($userData,$exchangeShopData,&$result){
            
            switch($exchangeShopData->exchange_product_id)
            {
                case 10001: // 通貨更新
                    $walletsData = UserWallet::where('manage_id',$userData->manage_id)->first();
                    $result = UserWallet::where('manage_id',$userData->manage_id)->update([
                        'free_amount' => $walletsData->free_amount + $exchangeShopData->exchange_item_amount,
                    ]);
                    break;
                case 20001: // スタミナアイテム更新
                    $staminaItemData = ItemInstance::where('manage_id',$userData->manage_id)->where('item_id',10001)->first();
                    $result = ItemInstance::where('manage_id',$userData->manage_id)->where('item_id',10001)->update([
                        'item_num' => $staminaItemData->item_num + $exchangeShopData->exchange_item_amount,
                    ]);
                    break;
                case 30001: // 強化ポイント更新
                    $result = Users::where('manage_id',$userData->manage_id)->update([
                        'has_reinforce_point' => $userData->has_reinforce_point + $exchangeShopData->exchange_item_amount,
                    ]);
                    break;
                default:
                break;
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

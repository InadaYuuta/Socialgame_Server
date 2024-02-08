<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\MasterDataService;
use App\Models\ItemsCategorie;
use App\Models\PaymentShop;

class MasterDataGetController extends Controller
{
    public function __invoke()
    {
        // クライアント側に送信したいマスターデータだけを選択
        $master_item_category = ItemsCategorie::GetItemsCategories();
        $payment_shop = PaymentShop::GetPaymentShop();

        $response = [
            'master_data_version' => config('constants.MASTER_DATA_VERSION'),
            'item_category' => $master_item_category,
            'payment_shop' => $payment_shop,
        ];

        return json_encode($response);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\MasterDataService;
use App\Models\ItemCategory;
use App\Models\PaymentShop;
use App\Models\Item;
use App\Models\ExchangeItemCategory;
use App\Models\ExchangeItemShop;
use App\Models\LogCategory;
use App\Models\Weapon;
use App\Models\WeaponCategory;
use App\Models\WeaponRarity;
use App\Models\GachaWeapon;

class MasterDataGetController extends Controller
{
    public function __invoke()
    {
        // クライアント側に送信したいマスターデータだけを選択
        $item = Item::GetItem();
        $payment_shop = PaymentShop::GetPaymentShop();
        $master_item_category = ItemCategory::GetItemCategory();
        $exchange_item_category = ExchangeItemCategory::GetExchangeItemCategory();
        $exchange_item_shop = ExchangeItemShop::GetExchangeItemShop();
        $log_category = LogCategory::GetLogCategory();
        $weapon_master = Weapon::GetWeaponMaster();
        $weapon_category = WeaponCategory::GetWeaponCategory();
        $weapon_rarity = WeaponRarity::GetWeaponRarity();
        $gacha_weapon = GachaWeapon::GetGachaWeapon();

        $response = [
            'master_data_version' => config('constants.MASTER_DATA_VERSION'),
            'item_master' => $item,
            'item_category'=>$master_item_category,
            'exchange_item_category' => $exchange_item_category,
            'log_category' => $log_category,
            'payment_shop' => $payment_shop,
            'exchange_item_shop' => $exchange_item_shop,
            'weapon_master' =>$weapon_master,
            'weapon_category' =>$weapon_category,
            'weapon_rarity' =>$weapon_rarity,
            'gacha_weapon' =>$gacha_weapon,
        ];

        return json_encode($response);
    }
}

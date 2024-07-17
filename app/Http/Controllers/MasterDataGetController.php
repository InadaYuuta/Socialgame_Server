<?php

namespace App\Http\Controllers;

// アイテム
use App\Models\Item;
use App\Models\ItemCategory;
// 武器
use App\Models\Weapon;
use App\Models\WeaponCategory;
use App\Models\WeaponRarity;
use App\Models\WeaponExp;
use App\Models\EvolutionWeapon;
// ショップ
use App\Models\PaymentShop;
use App\Models\ExchangeItemCategory;
use App\Models\ExchangeItemShop;
// ガチャ
use App\Models\GachaWeapon;
// ミッション
use App\Models\Mission;
use App\Models\MissionCategory;
// 報酬
use App\Models\RewardCategory;

class MasterDataGetController extends Controller
{
    public function __invoke()
    {
        // クライアント側に送信したいマスターデータだけを選択
        $item = Item::GetItem();
        $master_item_category = ItemCategory::GetItemCategory();
        $weapon_master = Weapon::GetWeaponMaster();
        $weapon_category = WeaponCategory::GetWeaponCategory();
        $weapon_rarity = WeaponRarity::GetWeaponRarity();
        $weapon_exp = WeaponExp::GetWeaponExp();
        $evolution_weapon = EvolutionWeapon::GetEvolutionWeapon();
        $payment_shop = PaymentShop::GetPaymentShop();
        $exchange_item_category = ExchangeItemCategory::GetExchangeItemCategory();
        $exchange_item_shop = ExchangeItemShop::GetExchangeItemShop();
        $gacha_weapon = GachaWeapon::GetGachaWeapon();
        $mission_master = Mission::GetMission();
        $mission_category = MissionCategory::GetMissionCategory();
        $reward_category = RewardCategory::GetRewardCategory();

        $response = [
            'master_data_version' => config('constants.MASTER_DATA_VERSION'),
            'item_master' => $item,
            'item_category'=>$master_item_category,
            'weapon_master' =>$weapon_master,
            'weapon_category' =>$weapon_category,
            'weapon_rarity' =>$weapon_rarity,
            'weapon_exp' => $weapon_exp,
            'evolution_weapon' => $evolution_weapon,
            'payment_shop' => $payment_shop,
            'exchange_item_category' => $exchange_item_category,
            'exchange_item_shop' => $exchange_item_shop,
            'gacha_weapon' =>$gacha_weapon,
            'mission_master' =>$mission_master,
            'mission_category' =>$mission_category,
            'reward_category' =>$reward_category,
        ];

        return json_encode($response);
    }
}

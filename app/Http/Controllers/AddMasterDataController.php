<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemCategory;
use App\Models\PaymentShop;
use App\Models\Item;
use App\Models\ExchangeItemCategory;
use App\Models\ExchangeItemShop;
use App\Models\LogCategory;
// --武器
use App\Models\Weapon;
use App\Models\WeaponCategory;
use App\Models\WeaponRarity;
// --ガチャ
use App\Models\GachaWeapon;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AddMasterDataController extends Controller
{
    public function __invoke()
    {
        // 追加データ(アイテムカテゴリー)
        $addItemCategoryData = [
            [
                'item_category' => 1,
                'category_name' => 'スタミナ回復アイテム',
            ],
            [
                'item_category' => 2,
                'category_name' => '強化ポイント',
            ],
            [
                'item_category' => 3,
                'category_name' => '交換アイテム',
            ],
            [
                'item_category' => 4,
                'category_name' => '凸アイテム',
            ],
        ];

        // 追加データ(アイテム)
        $addItemData = [
            [
                'item_id' => 10001,
                'item_category' => 1,
                'item_name' => 'スタミナ回復アイテム',
            ],
            [
                'item_id' => 20001,
                'item_category' => 2,
                'item_name' => '強化ポイント',
            ],
            [
                'item_id' => 30001,
                'item_category' => 3,
                'item_name' => '交換アイテム',
            ],
            [
                'item_id' => 40001,
                'item_category' => 4,
                'item_name' => '凸アイテム',
            ],
        ];

        // 追加データ(通貨ショップ商品)
        $addPaymentShopData = [
            [
                'product_id' => 10001,
                'product_name' => '通貨10個',
                'price' => 120,
                'paid_currency' => 10,
                'bonus_currency' => 0,
            ],
            [
                'product_id' => 10002,
                'product_name' => '通貨50個',
                'price' => 480,
                'paid_currency' => 40,
                'bonus_currency' => 10,
            ],
            [
                'product_id' => 10003,
                'product_name' => '通貨210個',
                'price' => 1600,
                'paid_currency' => 130,
                'bonus_currency' => 80,
            ],
            [
                'product_id' => 10004,
                'product_name' => '通貨410個',
                'price' => 3000,
                'paid_currency' => 250,
                'bonus_currency' => 160,
            ],
            [
                'product_id' => 10005,
                'product_name' => '通貨770個',
                'price' => 4900,
                'paid_currency' => 420,
                'bonus_currency' => 350,
            ],
            [
                'product_id' => 10006,
                'product_name' => '通貨1680個',
                'price' => 10000,
                'paid_currency' => 860,
                'bonus_currency' => 820,
            ],
        ];

        // 追加データ(交換ショップのカテゴリー)
        $addExchangeShopCategory = [
            [
                'exchange_item_category' => 1,
                'category_name' => '通貨',
            ],
            [
                'exchange_item_category' => 2,
                'category_name' => 'スタミナ回復アイテム',
            ],
            [
                'exchange_item_category' => 3,
                'category_name' => '強化ポイント',
            ],
        ];

        // 追加データ(交換ショップの商品)
        $addExchangeShopData = [
            [
                'exchange_product_id' => 10001,
                'exchange_item_category' => 1,
                'exchange_item_name' => '通貨30個',
                'exchange_item_amount' => 30,
                'exchange_price' => 10,
            ],
            [
                'exchange_product_id' => 20001,
                'exchange_item_category' => 2,
                'exchange_item_name' => 'スタミナアイテム1個',
                'exchange_item_amount' => 1,
                'exchange_price' => 10,
            ],
            [
                'exchange_product_id' => 30001,
                'exchange_item_category' => 3,
                'exchange_item_name' => '強化ポイント1000',
                'exchange_item_amount' => 1000,
                'exchange_price' => 10,
            ],
        ];

        // 追加データ(ログの種類)
        $addLogCategory = [
            [
                'log_category' => 1,
                'category_name' => 'プレイヤー情報更新',
            ],
            [
                'log_category' => 2,
                'category_name' => '通貨情報更新',
            ],
            [
                'log_category' => 3,
                'category_name' => 'アイテム情報更新',
            ],
            [
                'log_category' => 4,
                'category_name' => '武器情報更新',
            ],
            [
                'log_category' => 5,
                'category_name' => 'ミッション情報更新',
            ],
            [
                'log_category' => 6,
                'category_name' => 'シーズンパス情報更新',
            ],
            [
                'log_category' => 7,
                'category_name' => 'プレゼントボックス情報更新',
            ],
        ];

        // 追加データ(武器マスターデータ) TODO:今回は先生の確認なしで追加しているため、これ以下のデータは本実装時は内容を変更する
        $addMasterWeapon = [
            [
                'weapon_id' => 1010001,
                'rarity_id' => 1,
                'weapon_category' =>1,
                'weapon_name' =>'普通の剣',
            ],
            [
                'weapon_id' =>3010001,
                'rarity_id' =>3,
                'weapon_category' =>1,
                'weapon_name' =>'めっちゃ強い剣',
            ],
            [
                'weapon_id' =>1020001,
                'rarity_id' =>1,
                'weapon_category' =>2,
                'weapon_name' =>'普通の弓',
            ],
            [
                'weapon_id' =>2020001,
                'rarity_id' =>2,
                'weapon_category' =>2,
                'weapon_name' =>'強い弓',
            ],
            [
                'weapon_id' =>1030001,
                'rarity_id' =>1,
                'weapon_category' =>3,
                'weapon_name' =>'普通の槍',
            ],
        ];

        // 追加データ(武器カテゴリーデータ)
        $addWeaponCategory = [
            [
                'weapon_category' => 1,
                'category_name' =>'SWORD',
            ],
            [
                'weapon_category' => 2,
                'category_name' =>'BOW',
            ],
            [
                'weapon_category' => 3,
                'category_name' =>'SPEAR',
            ],
        ];

        // 追加データ(武器レアリティデータ)
        $addWeaponRarity = [
            [
                'rarity_id'=>1,
                'rarity_name'=>'COMON',
            ],
            [
                'rarity_id'=>2,
                'rarity_name'=>'RARE',
            ],
            [
                'rarity_id'=>3,
                'rarity_name'=>'SRARE',
            ],
        ];

        // 追加データ(ガチャ武器データ)
        $addGachaWeaponData = [
            [
                'gacha_id' => 100001,
                'weapon_id' => 1010001,
                'weight' => 26666,
            ],
            [
                'gacha_id' => 100001,
                'weapon_id' => 3010001,
                'weight' => 3000,
            ],
            [
                'gacha_id' => 100001,
                'weapon_id' => 1020001,
                'weight' => 26666,
            ],
            [
                'gacha_id' => 100001,
                'weapon_id' => 2020001,
                'weight' => 17000,
            ],
            [
                'gacha_id' => 100001,
                'weapon_id' => 1030001,
                'weight' => 26666,
            ],
        ];

        // 指定されたIDの情報が無かったら追加する TODO: useの中身が多すぎるからそれも連想配列にする
        DB::transaction(function() use($addItemCategoryData,$addItemData,$addPaymentShopData,$addExchangeShopCategory,$addExchangeShopData,$addLogCategory,$addMasterWeapon,$addWeaponCategory,$addWeaponRarity,$addGachaWeaponData){
            
            foreach($addItemCategoryData as $data)
            {
                $check = ItemCategory::where('item_category',$data['item_category'])->first();
                if($check == null)
                {
                    ItemCategory::create([
                        'item_category'=>$data['item_category'],
                        'category_name'=>$data['category_name'],
                    ]);
                }
            }

            foreach($addItemData as $data)
            {
                $check = Item::where('item_id',$data['item_id'])->first();
                if($check == null)
                {
                    Item::create([
                        'item_id'=>$data['item_id'],
                        'item_category'=>$data['item_category'],
                        'item_name'=>$data['item_name'],
                    ]);
                }
            }

            foreach ($addPaymentShopData as $data) {
                $check = PaymentShop::where('product_id',$data['product_id'])->first();
                if($check == null)
                {
                    PaymentShop::create([
                        'product_id' => $data['product_id'],
                        'product_name' => $data['product_name'],
                        'price' => $data['price'],
                        'paid_currency' => $data['paid_currency'],
                        'bonus_currency' => $data['bonus_currency'],
                    ]);
                }   
            }

            foreach($addExchangeShopCategory as $data)
            {
                $check = ExchangeItemCategory::where('exchange_item_category',$data['exchange_item_category'])->first();
                if($check == null)
                {
                    ExchangeItemCategory::create([
                        'exchange_item_category'=>$data['exchange_item_category'],
                        'category_name'=>$data['category_name'],
                    ]);
                }
            }

            foreach($addExchangeShopData as $data)
            {
                $check = ExchangeItemShop::where('exchange_product_id',$data['exchange_product_id'])->first();
                if($check == null)
                {
                    ExchangeItemShop::create([
                        'exchange_product_id'=>$data['exchange_product_id'],
                        'exchange_item_category'=>$data['exchange_item_category'],
                        'exchange_item_name'=>$data['exchange_item_name'],
                        'exchange_item_amount'=>$data['exchange_item_amount'],
                        'exchange_price'=>$data['exchange_price'],
                    ]);
                }
            }

            foreach($addLogCategory as $data)
            {
                $check = LogCategory::where('log_category',$data['log_category'])->first();
                if($check == null)
                {
                    LogCategory::create([
                        'log_category'=>$data['log_category'],
                        'category_name'=>$data['category_name'],
                    ]);
                }
            }

            foreach($addMasterWeapon as $data)
            {
                $check = Weapon::where('weapon_id',$data['weapon_id'])->first();
                if($check == null)
                {
                    Weapon::create([
                        'weapon_id'=>$data['weapon_id'],
                        'rarity_id' => $data['rarity_id'],
                        'weapon_category' =>$data['weapon_category'],
                        'weapon_name' =>$data['weapon_name'],
                    ]);
                }
            }

            foreach($addWeaponCategory as $data)
            {
                $check = WeaponCategory::where('weapon_category',$data['weapon_category'])->first();
                if($check == null)
                {
                    WeaponCategory::create([
                        'weapon_category'=>$data['weapon_category'],
                        'category_name'=>$data['category_name'],
                    ]);
                }
            }
            
            foreach($addWeaponRarity as $data)
            {
                $check = WeaponRarity::where('rarity_id',$data['rarity_id'])->first();
                if($check == null)
                {
                    WeaponRarity::create([
                        'rarity_id'=>$data['rarity_id'],
                        'rarity_name'=>$data['rarity_name'],
                    ]);
                }
            }
            
            foreach($addGachaWeaponData as $data)
            {
                $check = GachaWeapon::where('weapon_id',$data['weapon_id'])->first();
                if($check == null)
                {
                    GachaWeapon::create([
                        'gacha_id' => $data['gacha_id'],
                        'weapon_id' => $data['weapon_id'],
                        'weight' => $data['weight'],
                    ]);
                }
            }
        });
    }
}
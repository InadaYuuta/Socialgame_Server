<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemCategory;
use App\Models\PaymentShop;
use App\Models\Item;
use App\Models\ExchangeItemCategory;
use App\Models\ExchangeItemShop;
use App\Models\LogCategory;
use App\Models\RewardCategory;
// --武器
use App\Models\Weapon;
use App\Models\WeaponCategory;
use App\Models\WeaponRarity;
use App\Models\WeaponExp;
use App\Models\EvolutionWeapon;
// --ガチャ
use App\Models\GachaWeapon;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AddMasterDataController extends Controller
{
    /* マスターデータを追加する TODO:ここはできたらRequestを使ってまとめて外部から追加できるようにしたい */
    public function __invoke()
    {
        // 追加データ(アイテムカテゴリー)
        $addItemCategoryData = [
            [
                'item_category' => 1,
                'category_name' => 'STAMINA_RECOVERY_ITEM', // スタミナ回復アイテム
            ],
            [
                'item_category' => 2,
                'category_name' => 'REINFORCE_POINT', // 強化ポイント
            ],
            [
                'item_category' => 3,
                'category_name' => 'EXCHANGE_ITEM', // 交換アイテム
            ],
            [
                'item_category' => 4,
                'category_name' => 'COVEX_ITEM', // 凸アイテム
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
            [
                'item_id' => 40002,
                'item_category' => 4,
                'item_name' => '普通の剣凸アイテム',
            ],
            [
                'item_id' => 40003,
                'item_category' => 4,
                'item_name' => '普通の弓凸アイテム',
            ],
            [
                'item_id' => 40004,
                'item_category' => 4,
                'item_name' => '普通の槍凸アイテム',
            ],
            [
                'item_id' => 40005,
                'item_category' => 4,
                'item_name' => '強い弓凸アイテム',
            ],
            [
                'item_id' => 40006,
                'item_category' => 4,
                'item_name' => 'めっちゃ強い剣凸アイテム',
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
                'category_name' => 'CURRENCY', // 通貨
            ],
            [
                'exchange_item_category' => 2,
                'category_name' => 'STAMINA_RECOVERY_ITEM', // スタミナ回復アイテム
            ],
            [
                'exchange_item_category' => 3,
                'category_name' => 'REINFORCE_POINT', // 強化ポイント
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
                'category_name' => 'PLAYER', // プレイヤー情報更新
            ],
            [
                'log_category' => 2,
                'category_name' => 'CURRENCY', // 通貨情報更新
            ],
            [
                'log_category' => 3,
                'category_name' => 'ITEM', // アイテム情報更新
            ],
            [
                'log_category' => 4,
                'category_name' => 'WEAPON', // 武器情報更新
            ],
            [
                'log_category' => 5,
                'category_name' => 'MISSION', // ミッション情報更新
            ],
            [
                'log_category' => 6,
                'category_name' => 'SEASONPASS', // シーズンパス情報更新
            ],
            [
                'log_category' => 7,
                'category_name' => 'PREZENTBOX', // プレゼントボックス情報更新
            ],
        ];

        // 追加データ(武器マスターデータ) TODO:今回は先生の確認なしで追加しているため、これ以下のデータは本実装時は内容を変更する
        $addMasterWeapon = [
            [
                'weapon_id' => 1010001,
                'rarity_id' => 1,
                'weapon_category' =>1,
                'weapon_name' =>'普通の剣',
                'evolution_weapon_id'=>1110001,
            ],
            [
                'weapon_id' =>3010001,
                'rarity_id' =>3,
                'weapon_category' =>1,
                'weapon_name' =>'めっちゃ強い剣',
                'evolution_weapon_id'=>3110001,
            ],
            [
                'weapon_id' =>1020001,
                'rarity_id' =>1,
                'weapon_category' =>2,
                'weapon_name' =>'普通の弓',
                'evolution_weapon_id'=>1120001,
            ],
            [
                'weapon_id' =>2020001,
                'rarity_id' =>2,
                'weapon_category' =>2,
                'weapon_name' =>'強い弓',
                'evolution_weapon_id'=>2120001,
            ],
            [
                'weapon_id' =>1030001,
                'rarity_id' =>1,
                'weapon_category' =>3,
                'weapon_name' =>'普通の槍',
                'evolution_weapon_id'=>1130001,
            ],
        ];

        // 追加データ(進化後武器データ)
        $addEvolutionWeaponData = [
            [
                'evolution_weapon_id'=>1110001,
                'rarity_id' => 4,
                'weapon_category' =>1,
                'weapon_name' =>'普通の剣+',
            ],
            [
                'evolution_weapon_id'=>3110001,
                'rarity_id' => 6,
                'weapon_category' =>1,
                'weapon_name' =>'めっちゃ強い剣+',
            ],
            [
                'evolution_weapon_id'=>1120001,
                'rarity_id' => 4,
                'weapon_category' =>2,
                'weapon_name' =>'普通の弓+',
            ],
            [
                'evolution_weapon_id'=>2120001,
                'rarity_id' =>5,
                'weapon_category' =>2,
                'weapon_name' =>'強い弓+',
            ],
            [
                'evolution_weapon_id'=>1130001,
                'rarity_id' =>4,
                'weapon_category' =>3,
                'weapon_name' =>'普通の槍+',
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
            [
                'rarity_id'=>4,
                'rarity_name'=>'COMON+',
            ],
            [
                'rarity_id'=>5,
                'rarity_name'=>'RARE+',
            ],
            [
                'rarity_id'=>6,
                'rarity_name'=>'SRARE+',
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

        // 追加データ(武器必要経験値データ)
        $addWeaponExpData = [
            [
                'rarity_id' =>1, // Comon
                'use_reinforce_point_level_1_to_20'=>100,  // 1~20レベルの間で必要な経験値
                'use_reinforce_point_level_21_to_40'=>200, // 21~40レベルの間で必要な経験値
                'use_reinforce_point_level_41'=>250,
                'use_reinforce_point_level_42'=>300,
                'use_reinforce_point_level_43'=>350,
                'use_reinforce_point_level_44'=>400,
                'use_reinforce_point_level_45'=>450,
                'use_reinforce_point_level_46'=>500,
                'use_reinforce_point_level_47'=>550,
                'use_reinforce_point_level_48'=>600,
                'use_reinforce_point_level_49'=>650,
                'use_reinforce_point_level_50'=>700,
            ],
            [
                'rarity_id' =>2, // Rare
                'use_reinforce_point_level_1_to_20'=>150,  // 1~20レベルの間で必要な経験値
                'use_reinforce_point_level_21_to_40'=>250, // 21~40レベルの間で必要な経験値
                'use_reinforce_point_level_41'=>350,
                'use_reinforce_point_level_42'=>450,
                'use_reinforce_point_level_43'=>550,
                'use_reinforce_point_level_44'=>650,
                'use_reinforce_point_level_45'=>750,
                'use_reinforce_point_level_46'=>850,
                'use_reinforce_point_level_47'=>950,
                'use_reinforce_point_level_48'=>1050,
                'use_reinforce_point_level_49'=>1150,
                'use_reinforce_point_level_50'=>1250,
            ],
            [
                'rarity_id' =>3, // SRare
                'use_reinforce_point_level_1_to_20'=>200,  // 1~20レベルの間で必要な経験値
                'use_reinforce_point_level_21_to_40'=>300, // 21~40レベルの間で必要な経験値
                'use_reinforce_point_level_41'=>500,
                'use_reinforce_point_level_42'=>700,
                'use_reinforce_point_level_43'=>900,
                'use_reinforce_point_level_44'=>1100,
                'use_reinforce_point_level_45'=>1300,
                'use_reinforce_point_level_46'=>1500,
                'use_reinforce_point_level_47'=>1700,
                'use_reinforce_point_level_48'=>1900,
                'use_reinforce_point_level_49'=>2100,
                'use_reinforce_point_level_50'=>2300,
            ],

        ];

        // 追加データ(報酬カテゴリー)
        $addRewardCategory = [
            [
                'reward_category'=>1,
                'reward_category_name'=>'Payment', // 通貨
            ],
            [
                'reward_category'=>2,
                'reward_category_name'=>'StaminaRecoveryItem', // スタミナ回復アイテム
            ],
            [
                'reward_category'=>3,
                'reward_category_name'=>'ReinforcePoint', // 強化ポイント
            ],
            [
                'reward_category'=>4,
                'reward_category_name'=>'ExchangeItem', // 交換アイテム
            ],
            [
                'reward_category'=>5,
                'reward_category_name'=>'ConvexItem', // 凸アイテム
            ],
            [
                'reward_category'=>6,
                'reward_category_name'=>'Weapon', // 武器
            ],
        ];


        // 指定されたIDの情報が無かったら追加する TODO: useの中身が多すぎるからそれも連想配列にする
        DB::transaction(function() use ($addItemCategoryData,$addItemData,$addPaymentShopData,$addExchangeShopCategory,$addExchangeShopData,$addLogCategory,$addMasterWeapon,$addEvolutionWeaponData,$addWeaponCategory,$addWeaponRarity,$addGachaWeaponData,$addWeaponExpData,$addRewardCategory){
            
            // アイテムカテゴリーデータ
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

            // アイテムデータ
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

            // 通貨ショップデータ
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

            // 交換ショップカテゴリーデータ
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

            // 交換ショップデータ
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

            // ログカテゴリー
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

            // 武器カテゴリーデータ
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
            
            // 武器レアリティデータ
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
            
            // 進化後武器マスタデータ
            foreach($addEvolutionWeaponData as $data)
            {
                $check = EvolutionWeapon::where('evolution_weapon_id',$data['evolution_weapon_id'])->first();
                if($check == null)
                {
                    EvolutionWeapon::create([
                        'evolution_weapon_id'=>$data['evolution_weapon_id'],
                        'rarity_id' => $data['rarity_id'],
                        'weapon_category' =>$data['weapon_category'],
                        'weapon_name' =>$data['weapon_name'],
                    ]);
                }
            }

            // 武器マスタデータ
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
                        'evolution_weapon_id' => $data['evolution_weapon_id'],
                    ]);
                }
            }

            // ガチャ武器データ
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

            // 武器必要経験値
            foreach($addWeaponExpData as $data)
            { 
                for($i = 1; $i<51;$i++)
                {
                    $check = WeaponExp::where('rarity_id',$data['rarity_id'])->where('level',$i)->first();
                    if($check == null)
                    {
                        $rarity_id = $data['rarity_id'];
                        $level = $i;

                        if($level<21)
                        {
                            WeaponExp::create([
                                'rarity_id'=>$rarity_id,
                                'level'=>$level,
                                'use_reinforce_point'=>$data['use_reinforce_point_level_1_to_20'],
                            ]);
                        }
                        else if($level > 20 && $level < 41)
                        {
                            WeaponExp::create([
                                'rarity_id'=>$rarity_id,
                                'level'=>$level,
                                'use_reinforce_point'=>$data['use_reinforce_point_level_21_to_40'],
                            ]);
                        }
                        else if($level > 40 && $level < 51)
                        {
                            switch($level)
                            {
                                case 41:
                                    WeaponExp::create([
                                        'rarity_id'=>$rarity_id,
                                        'level'=>$level,
                                        'use_reinforce_point'=>$data['use_reinforce_point_level_41'],
                                    ]);
                                    break;
                                case 42:
                                    WeaponExp::create([
                                        'rarity_id'=>$rarity_id,
                                        'level'=>$level,
                                        'use_reinforce_point'=>$data['use_reinforce_point_level_42'],
                                    ]);
                                    break;
                                case 43:
                                    WeaponExp::create([
                                        'rarity_id'=>$rarity_id,
                                        'level'=>$level,
                                        'use_reinforce_point'=>$data['use_reinforce_point_level_43'],
                                    ]);
                                    break;
                                case 44:
                                    WeaponExp::create([
                                        'rarity_id'=>$rarity_id,
                                        'level'=>$level,
                                        'use_reinforce_point'=>$data['use_reinforce_point_level_44'],
                                    ]);
                                    break;
                                case 45:
                                    WeaponExp::create([
                                        'rarity_id'=>$rarity_id,
                                        'level'=>$level,
                                        'use_reinforce_point'=>$data['use_reinforce_point_level_45'],
                                    ]);
                                    break;
                                case 46:
                                    WeaponExp::create([
                                        'rarity_id'=>$rarity_id,
                                        'level'=>$level,
                                        'use_reinforce_point'=>$data['use_reinforce_point_level_46'],
                                    ]);
                                    break;
                                case 47:
                                    WeaponExp::create([
                                        'rarity_id'=>$rarity_id,
                                        'level'=>$level,
                                        'use_reinforce_point'=>$data['use_reinforce_point_level_47'],
                                    ]);
                                    break;
                                case 48:
                                    WeaponExp::create([
                                        'rarity_id'=>$rarity_id,
                                        'level'=>$level,
                                        'use_reinforce_point'=>$data['use_reinforce_point_level_48'],
                                    ]);
                                    break;
                                case 49:
                                    WeaponExp::create([
                                        'rarity_id'=>$rarity_id,
                                        'level'=>$level,
                                        'use_reinforce_point'=>$data['use_reinforce_point_level_49'],
                                    ]);
                                    break;
                                case 50:
                                    WeaponExp::create([
                                        'rarity_id'=>$rarity_id,
                                        'level'=>$level,
                                        'use_reinforce_point'=>$data['use_reinforce_point_level_50'],
                                    ]);
                                    break;
                            }
                        }
                    }
                }

            }

            // 報酬カテゴリー
            foreach($addRewardCategory as $data)
            {
                $check = RewardCategory::where('reward_category',$data['reward_category'])->first();
                if($check == null)
                {
                    RewardCategory::create([
                        'reward_category'=>$data['reward_category'],
                        'reward_category_name'=>$data['reward_category_name'],
                     ]);
                }
            }
        });
    }
}
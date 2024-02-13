<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Libs\MasterDataService;

class ExchangeItemShop extends Model
{
    use HasFactory;

    protected $table = 'exchange_item_shops';
    protected $primarykey = 'exchange_product_id';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $guarded = [
        '',
    ];

    public static function GetExchangeItemShop()
    {
        $exchange_shop_data_list = MasterDataService::GetMasterData('exchange_item_shops');
        return $exchange_shop_data_list;
    }

    public static function GetExchangeItemShopByProductId($exchange_product_id)
    {
        $exchange_shop_data_list = self::GetPaymentShop();
        foreach ($exchange_shop_data_list as $exchange_shop_data)
        {
            $exchange_data = new ExchangeItemShop;
            $exchange_data->exchange_product_id = $exchange_shop_data['exchange_product_id'];
            $exchange_data->exchange_item_category = $exchange_shop_data['exchange_item_category'];
            $exchange_data->exchange_item_name = $exchange_shop_data['exchange_item_name']; 
            $exchange_data->exchange_item_amount = $exchange_shop_data['exchange_item_amount']; 
            $exchange_data->exchange_price = $exchange_shop_data['exchange_price'];
            if($exchange_product_id == $exchange_data->exchange_product_id)
            {
                return $exchange_data;
            }
        }
        return null;
    }
}
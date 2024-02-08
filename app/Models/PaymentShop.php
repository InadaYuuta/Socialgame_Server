<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Libs\MasterDataService;

class PaymentShop extends Model
{
    use HasFactory;

    protected $table = 'payment_shops';
    protected $primarykey = 'product_id';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $guarded = [
    ];

    public static function GetPaymentShop()
    {
        $payment_shpo_data_list = MasterDataService::GetMasterData('payment_shop');
        return $payment_shpo_data_list;
    }

    public static function GetPaymentShopByProductId($product_id)
    {
        $payment_shop_data_list = self::GetPaymentShop();
        foreach ($payment_shop_data_list as $payment_shop_data)
        {
            $payment_data = new PaymentShop;
            $payment_data->product_id = $payment_shop_data['product_id'];
            $payment_data->product_name = $payment_shop_data['product_name'];
            $payment_data->price = $payment_shop_data['price']; 
            $payment_data->paid_currency = $payment_shop_data['paid_currency']; 
            $payment_data->bonus_currency = $payment_shop_data['bonus_currency'];
            if($product_id == $payment_data->product_id)
            {
                return $payment_data;
            }
        }
        return null;
    }
}

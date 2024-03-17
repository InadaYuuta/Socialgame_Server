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

   // 変更を許可しないカラムのリスト
    protected $guarded = [
        'created',
    ];

    // マスタデータ取得
    public static function GetExchangeItemShop()
    {
        $exchange_shop_data_list = MasterDataService::GetMasterData('exchange_item_shops');
        return $exchange_shop_data_list;
    }
}
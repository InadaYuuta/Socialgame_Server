<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Libs\MasterDataService;

class ExchangeItemCategory extends Model
{
    use HasFactory;

    protected $table = 'exchange_item_categories';
    protected $primarykey = 'exchange_item_category';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $guarded = [
        '',
    ];

    public static function GetExchangeItemCategory()
    {
        $exchange_item_category_list = MasterDataService::GetMasterData('exchange_item_categories');
        return $exchange_item_category_list;
    }

    public static function GetExchangeItemCategories($exchange_item_category)
    {
        $exchange_item_category_list = self::GetExchangeItemCategory();
        foreach ($exchange_item_category_list as $exchange_item_category_data)
        {
            $exchange_item_data = new ExchangeItemCategory;
            $exchange_item_data->exchange_item_category = $exchange_item_category_data['exchange_item_category'];
            $exchange_item_data->category_name = $exchange_item_category_data['category_name'];
            if($exchange_item_category == $exchange_item_data->exchange_item_category)
            {
                return $exchange_item_data;
            }
        }
        return null;
    }
}

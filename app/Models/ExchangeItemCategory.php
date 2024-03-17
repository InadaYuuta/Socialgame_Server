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

    // 変更を許可しないカラムのリスト
    protected $guarded = [
        'created',
    ];

    // マスタデータ取得
    public static function GetExchangeItemCategory()
    {
        $exchange_item_category_list = MasterDataService::GetMasterData('exchange_item_categories');
        return $exchange_item_category_list;
    }
}

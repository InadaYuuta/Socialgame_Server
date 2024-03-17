<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Libs\MasterDataService;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items';
    protected $primarykey = 'item_id';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    // 変更を許可しないカラムのリスト
    protected $guarded = [
        'created',
    ];

    // マスタデータ取得
    public static function GetItem()
    {
        $item_data_list = MasterDataService::GetMasterData('item_master');
        return $item_data_list;
    }
}

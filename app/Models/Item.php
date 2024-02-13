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

    protected $guarded = [
        '',
    ];

    public static function GetItem()
    {
        $item_data_list = MasterDataService::GetMasterData('item_master');
        return $item_data_list;
    }

    public static function GetItemByItemId($item_id)
    {
        $item_data_list = self::GetItem();
        foreach ($item_data_list as $item_data)
        {
            $item_master_data = new Item;
            $item_master_data->item_id = $item_data['item_id'];
            $item_master_data->item_name = $item_data['item_name'];
            $item_master_data->item_category = $item_data['item_category'];

            if($item_id == $item_master_data->item_id)
            {
                return $item_master_data;
            }
        }
        return null;
    }
}

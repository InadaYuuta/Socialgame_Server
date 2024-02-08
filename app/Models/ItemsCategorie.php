<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Libs\MasterDataService;

class ItemsCategorie extends Model
{
    use HasFactory;

    protected $table = 'items_categories';
    protected $primarykey = 'item_category';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $guarded = [
        '',
    ];

    public static function GetItemsCategories()
    {
        $master_data_list = MasterDataService::GetMasterData('items_category');
        return $master_data_list;
    }

    public static function GetItemsCategoriesByItemCategory($item_category)
    {
        $master_data_list = self::GetItemsCategories(); // selfは自クラスを示す。staticメソッドにアクセスできる
        foreach($master_data_list as $master_data){
            $item_categories = new GetItemsCategories;
            $item_categories->item_category = $master_data['item_category'];
            $item_categories->category_name = $master_data['category_name'];
            if($item_category == $item_categories->item_category){
                return $item_categories;
            }
        }
        return null;
    }
}

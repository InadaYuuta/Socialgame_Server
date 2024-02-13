<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Libs\MasterDataService;

class LogCategory extends Model
{
    use HasFactory;

    protected $table = 'log_categories';
    protected $primarykey = 'log_category';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $guarded = [
    ];

    public static function GetLogCategory()
    {
        $log_category_data_list = MasterDataService::GetMasterData('logCategory');
        return $log_category_data_list;
    }

    public static function GetLogCategoryByLogCategory($log_category)
    {
        $log_category_data_list = self::GetLogCategory();
        foreach ($log_category_data_list as $log_category_data)
        {
            $log_data = new LogCategory;
            $log_data->log_category = $log_category_data['log_category'];
            $log_data->category_name = $log_category_data['category_name'];
            if($log_category == $log_data->log_category)
            {
                return $log_data;
            }
        }
        return null;
    }
}

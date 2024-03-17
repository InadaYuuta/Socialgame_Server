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

    // 変更を許可しないカラムのリスト
    protected $guarded = [
        'created',
    ];

    // マスタデータ取得
    public static function GetLogCategory()
    {
        $log_category_data_list = MasterDataService::GetMasterData('logCategory');
        return $log_category_data_list;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MissionCategory extends Model
{
    use HasFactory;

    protected $table = 'mission_categories';
    protected $primarykey = 'mission_category';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    // 変更を許可しないカラムのリスト
    protected $guarded = [
        'created',
    ];

    // マスタデータ取得
    public static function GetMissionCategory()
    {
        $mission_category_data_list = MasterDataService::GetMasterData('missionCategory');
        return $mission_category_data_list;
    }
}

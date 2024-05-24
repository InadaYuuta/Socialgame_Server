<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Libs\MasterDataService;

class Mission extends Model
{
    use HasFactory;

    protected $table = 'missions';
    protected $primarykey = 'mission_id';
    protected $index = 'mission_category';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

   // 変更を許可しないカラムのリスト
    protected $guarded = [
        'created',
    ];

    // マスタデータ取得
    public static function GetMission()
    {
        $mission_data_list = MasterDataService::GetMasterData('mission');
        return $mission_data_list;
    }
}

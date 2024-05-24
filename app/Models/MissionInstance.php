<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Libs\MasterDataService;

class MissionInstance extends Model
{
    use HasFactory;

    protected $table = 'mission_instances';
    protected $primarykey = ['manage_id','mission_id'];

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    // 変更を許可しないカラムのリスト
    protected $guarded = [
        'created',
    ];

    // マスタデータ取得
    public static function GetMissionInstance()
    {
        $mission_instance_data_list = MasterDataService::GetMasterData('missionInstance');
        return $mission_instance_data_list;
    }
}

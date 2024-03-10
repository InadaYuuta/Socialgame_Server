<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    use HasFactory;

    protected $table = 'missions';
    protected $primarykey = 'mission_id';
    protected $index = 'mission_category';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $guarded = [
    ];

    public static function GetMission()
    {
        $mission_data_list = MasterDataService::GetMasterData('mission');
        return $mission_data_list;
    }
}

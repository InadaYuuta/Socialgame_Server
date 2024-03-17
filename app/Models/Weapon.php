<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Libs\MasterDataService;

class Weapon extends Model
{
    use HasFactory;

    protected $table = 'weapons';
    protected $primarykey = 'weapon_id';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    // 変更を許可しないカラムのリスト
    protected $guarded = [
        'created',
    ];

    // マスタデータ取得
    public static function GetWeaponMaster()
    {
        $master_data_list = MasterDataService::GetMasterData('weapon_master');
        return $master_data_list;
    }
}

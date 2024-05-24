<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Libs\MasterDataService;

class EvolutionWeapon extends Model
{
    use HasFactory;

    protected $table = 'evolution_weapons';
    protected $primarykey = 'evolution_weapon_id';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    // 変更を許可しないカラムのリスト
    protected $guarded = [
        'created',
    ];

    // マスタデータを取得する
    public static function GetEvolutionWeapon()
    {
        $evolution_weapon_master_data_list = MasterDataService::GetMasterData('evolution_weapon');
        return $evolution_weapon_master_data_list;
    }
}

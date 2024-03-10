<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvolutionWeapon extends Model
{
    use HasFactory;

    protected $table = 'evolution_weapons';
    protected $primarykey = 'evolution_weapon_id';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $guarded = [
        'evolution_weapons',
    ];

    public static function GetWeaponMaster()
    {
        $master_data_list = MasterDataService::GetMasterData('weapon_master');
        return $master_data_list;
    }
}

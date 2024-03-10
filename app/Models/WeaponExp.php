<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Libs\MasterDataService;

class WeaponExp extends Model
{
    use HasFactory;
    protected $table = 'weapon_exps';
    protected $primarykey = '';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $guarded = [
    ];

    public static function GetWeaponExp()
    {
        $master_data_list = MasterDataService::GetMasterData('weapon_exp');
        return $master_data_list;
    }
}

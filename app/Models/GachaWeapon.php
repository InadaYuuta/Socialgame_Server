<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Libs\MasterDataService;

class GachaWeapon extends Model
{
    use HasFactory;

    protected $table = 'gacha_weapons';
    protected $primarykey = 'weapon_id';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $guarded = [
    ];

    public static function GetGachaWeapon()
    {
        $master_data_list = MasterDataService::GetMasterData('gacha_weapon');
        return $master_data_list;
    }
}

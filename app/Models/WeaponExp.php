<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Libs\MasterDataService;

class WeaponExp extends Model
{
    use HasFactory;
    protected $table = 'weapon_exps';
    protected $primarykey = ['rarity_id','level'];

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    // 変更を許可しないカラムのリスト
    protected $guarded = [
        'created',
    ];

    // マスタデータ取得
    public static function GetWeaponExp()
    {
        $master_data_list = MasterDataService::GetMasterData('weapon_exp');
        return $master_data_list;
    }
}

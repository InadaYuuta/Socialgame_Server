<?php
namespace App\Libs;


use App\Models\Weapon;
use App\Models\WeaponExp;

class WeaponService
{
    /**
     * 武器情報から強化に必要なポイントを取得
     */
    public static function needReinforcePoint($weapon_data) :int
    {
        // 強化する武器の情報を取得
        $weapon_id = $weapon_data->weapon_id;
        $current_level = $weapon_data->level;

        // 武器マスターデータからレアリティを取得
        $weapon_master_data = Weapon::where('weapon_id',$weapon_id)->first();
        $rarity_id = $weapon_master_data ->rarity_id;

        // レアリティと現在のレベルから必要なポイントを取得
        $weapon_exp_master_data = WeaponExp::where('rarity_id',$rarity_id)->where('level',$current_level + 1)->first();
        $required_point = $weapon_exp_master_data->use_reinforce_point;

        return $required_point;
    }
}
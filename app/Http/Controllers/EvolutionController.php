<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use App\Models\WeaponInstance;
use App\Models\Weapon;
use App\Models\EvolutionWeapon;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class EvolutionController extends Controller
{
    public function __invoke(Request $request)
    {
        $result = 0;
        $errmsg = '';
        // ユーザー情報取得
        $userData = Users::where('user_id',$request->uid)->first();
        // 強化する武器のデータ取得
        $weaponData = WeaponInstance::where('manage_id',$userData->manage_id)->where('weapon_id',$request->wid)->first();
        // 武器マスタデータ取得
        $masterWeaponData = Weapon::where('weapon_id',$weaponData->weapon_id)->first();
        // 進化する武器の情報
        $evolutionData = EvolutionWeapon::where('evolution_weapon_id',$masterWeaponData->evolution_weapon_id)->first();

        $has_reinforce_point = $userData->has_reinforce_point;

        // 武器を進化
        DB::transaction(function() use($userData,$weaponData,$masterWeaponData,$evolutionData,$result,$request,$has_reinforce_point){
            
            // 強化ポイントを減らす
            $result = Users::where('manage_id',$userData->manage_id)->update([
                'has_reinforce_point' => $has_reinforce_point-$request->rp,
            ]);
            // レベルアップ
            $result = WeaponInstance::where('manage_id',$userData->manage_id)->where('weapon_id',$request->wid)->update([
                'weapon_id'=>$evolutionData->evolution_weapon_id,
                'rarity_id'=>$evolutionData->rarity_id,
                'evolution'=>1,
            ]);
            $result = 1;
        });

        if($result > 0)
        {
            $response =[
                'users'=>Users::where('user_id',$request->uid)->first(),
                'weapons'=>WeaponInstance::where('manage_id',$userData->manage_id)->get(),
            ];
        }
        else
        {
            // エラーメッセージ表示
            $check = WeaponInstance::where('manage_id',$userData->manage_id)->where('weapon_id',$evolutionData->evolution_weapon_id)->first();
            if($check != null){$errmsg='所持している武器です';}
            if($weaponData==null){$errmsg='所持していない武器です';}
            if($weaponData->level <= 49){$errmsg='レベルが足りません';}
            if($has_reinforce_point < $request->rp){$errmsg='所持強化ポイントが足りません';}

            $response=$errmsg;
        }
        
        return json_encode($response);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use App\Models\WeaponInstance;
use App\Models\WeaponExp;
use App\Models\Weapon;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class LevelUpController extends Controller
{
    public function __invoke(Request $request)
    {
        $result = 0;
        // ユーザー情報取得
        $userData = Users::where('user_id',$request->uid)->first();
        // 強化する武器のデータ取得
        $weaponData = WeaponInstance::where('manage_id',$userData->manage_id)->where('weapon_id',$request->wid)->first();
        // 武器を強化
        DB::transaction(function() use($userData,$weaponData,$result,$request){
            if($weaponData==null){return json_encode("所持していない武器です");}
            if($weaponData->level >= 50){return json_encode("レベルが上限を超えています");}
            $has_reinforce_point = $userData->has_reinforce_point;
            if($has_reinforce_point < $request->rp){return json_encode("所持強化ポイントが足りません");}
            // 強化ポイントを減らす
            $result = Users::where('manage_id',$userData->manage_id)->update([
                'has_reinforce_point' => $has_reinforce_point-$request->rp,
            ]);

            // レベルアップ
            $result = WeaponInstance::where('manage_id',$userData->manage_id)->where('weapon_id',$request->wid)->update([
                'level'=>$weaponData->level + 1,
            ]);
        });

        $response =[
            'users'=>Users::where('user_id',$request->uid)->first(),
            'weapons'=>WeaponInstance::where('manage_id',$userData->manage_id)->get(),
        ];
        return json_encode($response);
    }
}

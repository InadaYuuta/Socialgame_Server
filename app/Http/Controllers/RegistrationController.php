<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use App\Models\Devices;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RegistrationController extends Controller
{
    public function __invoke(Request $request)
    {
       // ユーザーIDの決定
       $user_id = Str::ulid();
       
       // 生成したIDが重複していないかのチェックを入れる、生成できなかったら再度生成、できたら進む
       DB::transaction(function() use($user_id)
       {
           $checks = Users::select('user_id')->get();
           foreach($checks as $check)
           {
               if($user_id == $check)
               {
                   $user_id = Str::ulid(); // idの再生成
                }
            }
        });
        
        // 初期データ設定
        $users = new Users;
        $users->user_id = $user_id;
        $users->user_name = config('constants.USER_NAME');
        $users->handover_passhash = config('constants.HANDOVER_PASSHASH');
        $users->has_weapon_exp_point = config('constants.HAS_WEAPON_EXP_POINT');
        $users->user_rank = config('constants.USER_RANK');
        $users->login_days = config('constants.LOGIN_DAYS');
        $users->max_stamina = config('constants.MAX_STAMINA');
        $users->last_stamina = config('constants.LAST_STAMINA');
        
        $test = [];

        // 仮データの保存
        // クロージャ　関数を引数として渡すようなイメージの仕組み
        DB::transaction(function() use($users,$request,&$test)
        {
            $test = Users::create([
                'user_id'=>$users->user_id,
                'user_name'=>$request->un,
                'handover_passhash'=>$users->handover_passhash,
                'has_weapon_exp_point'=>$users->has_weapon_exp_point,
                'user_rank'=>$users->user_rank,
                'login_days'=>$users->login_days,
                'max_stamina'=>$users->max_stamina,
                'last_stamina'=>$users->last_stamina,
            ]);
        });
        
        $response = array(
            'usersModel' => $test,
        );

        return json_encode($response);

        // デバイス情報を保存するためのクロージャ
        // DB::transaction (function() use($request)
        // {
        //     $device_id = $request->did;
        //     $checks = Devices::select('device_id')->get();
        //     foreach($checks as $check)
        //     {
        //         if($device_id != $check){
        //             // 初期データ設定
        //             $devices = new Devices;
        //             $devices->$device_id=$request->did;   // デバイスID
        //             $devices->$user_id=$user_id;          // ユーザーID

        //             // テーブル作成
        //             $add = Devices::create([
        //                 'device_id'=>$devices->device_id,
        //                 'user_id'=>$devices->user_id,
        //             ]);
        //         }
        //     }
        // });

    }
}
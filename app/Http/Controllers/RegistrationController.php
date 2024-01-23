<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use App\Models\user_wallets;
use App\Models\Devices;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RegistrationController extends Controller
{
    public function __invoke(Request $request)
    {
        $usersData = [];
        $walletsData = [];
       // ユーザーテーブル作成
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
        

        // 仮データの保存
        // クロージャ　関数を引数として渡すようなイメージの仕組み
        DB::transaction(function() use($user_id,$request,&$usersData)
        {
            $usersData = Users::create([
                'user_id'=>$user_id,
                'user_name'=>$request->un,
                'handover_passhash'=>config('constants.HANDOVER_PASSHASH'),
                'has_weapon_exp_point'=>config('constants.HAS_WEAPON_EXP_POINT'),
                'user_rank'=>config('constants.USER_RANK'),
                'login_days'=>config('constants.LOGIN_DAYS'),
                'max_stamina'=>config('constants.MAX_STAMINA'),
                'last_stamina'=>config('constants.LAST_STAMINA'),
            ]);
        });

        $users_select = Users::where('user_id',$usersData->user_id)->first();
        $registId= $users_select->manage_id;
        
        // ウォレットの登録
        DB::transaction(function() use($registId,&$walletsData){
            $walletsData = user_wallets::create([
                'manage_id'=>$registId,
                'free_amount'=>config('constants.FREE_AMOUNT'),
                'paid_amount'=>config('constants.PAID_AMOUNT'),
                'max_amount'=>config('constants.MAX_AMOUNT'),
            ]);
        });

        // 値の保存
        $response = [
            'usersModel' => $usersData,
            'walletsModel' => $walletsData,
        ];

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
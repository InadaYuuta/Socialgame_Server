<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use App\Models\Devices;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        $last_login_time = [];
        // TODO: ここで指定されたuser_idからユーザーを取得して最終ログイン時間を更新する処理を書く
         DB::transaction(function() use($request,&$last_login_time)
        {
          $update = Users::where('user_id',$request->uid)->update([]); // 更新

            $users = Users::where('user_id',$request->uid)->first();// ユーザーIDからユーザーを取得
            if($users)
            {
                $last_login_time = $users->last_login;
            }
        });

        $response = array(
            "lastLoginTime"=>$last_login_time,
        );
        return json_encode($response);
    }
}

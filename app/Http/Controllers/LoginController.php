<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use App\Models\Devices;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        $result = 0;
        // ユーザー情報取得
        $userData = Users::where('user_id',$request->uid)->first();
         DB::transaction(function() use($userData,&$result)
        {
          $result = Users::where('manage_id',$userData->manage_id)->update([
            'last_login' => Carbon::now()->format('Y-m-d H:i:s'),
          ]); // 更新
        });
        $response['result'] = 0;
        if($result == 0)
        {
            $response['result'] = -1;
        }
        return json_encode($response);
    }
}

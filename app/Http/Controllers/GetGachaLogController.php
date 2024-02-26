<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use App\Models\GachaLog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class GetGachaLogController extends Controller
{
    public function __invoke(Request $request)
    {
        // ユーザー情報取得
        $userData = Users::where('user_id',$request->uid)->first();
        $response = [
            'gacha_log'=>GachaLog::where('manage_id',$userData->manage_id)->get(),
        ];

        return json_encode($response);
    }
}

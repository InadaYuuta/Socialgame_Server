<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\GameUtilService;
use App\Libs\ErrorUtilService;

use App\Models\User;
use App\Models\UserWallet;
use App\Models\WeaponInstance;
use App\Models\ItemInstance;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /* ログイン
    /* uid = ユーザーID
    */
    public function __invoke(Request $request)
    {
        $result = 0;
        $errcode = '';
        $response = 0;

        // ユーザー情報
        $userData = User::where('user_id',$request->uid)->first();

        // ユーザー管理ID
        $manage_id = $userData->manage_id;

         DB::transaction(function() use(&$result,$userData,$manage_id)
        {
           // ログ関連
           $log_category = 0;
           $log_context = '';

          $result = User::where('manage_id',$manage_id)->update([
            'last_login' => Carbon::now()->format('Y-m-d H:i:s'),
          ]); // ログイン時間更新

          // ログを追加する処理
          $userData = User::where('manage_id',$manage_id)->first();
          $log_category = config('constants.USER_DATA');
          $log_context = config('constants.LOGIN_USER').$userData;
          GameUtilService::logCreate($manage_id,$log_category,$log_context);

          $result = 1;
        });

        // TODO: 今後ログインボーナス等を実装するときはここに追記

        switch($result)
        {
            case 0:
                $errcode = config('constants.CANT_LOGIN');
                $response = [
                    'errcode' => $errcode,
                ];
                break;
            case 1:
                
                 // Authに登録
                Auth::login($userData);
                
                $response =[
                    'user' => User::where('manage_id',$manage_id)->first(),
                    'wallet'=> UserWallet::where('manage_id',$manage_id)->first(),
                    'weapons' => WeaponInstance::where('manage_id',$manage_id)->get(),
                    'items' => ItemInstance::where('manage_id',$manage_id)->get(),
                    // TODO: 他にログイン時に取得したい情報があればここに追記
                ];
                break;
        }

        return json_encode($response);
    }
}

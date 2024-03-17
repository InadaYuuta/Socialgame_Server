<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\GameUtilService;

use App\Models\User;
use App\Models\UserWallet;
use App\Models\Item;
use App\Models\ItemInstance;
use App\Models\Log;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
    /* ユーザー登録
    /* un = ユーザーの名前
    */
    public function __invoke(Request $request)
    {
        $result = 0;
        $errmsg = '';
        $response = 0;

        $usersData = [];
        $walletsData = [];
        $itemsData = [];

        // ユーザーデータ
        $userData = 0;

        //ユーザー管理ID
        $manage_id = 0;

        // ユーザーテーブル作成
        // ユーザーIDの決定
        $user_id = Str::ulid();

        // ユーザー名が0文字以下12文字以上かつ指定文字以外を使用していたならエラー
        $validator = Validator::make($request -> all(),['un' => 'required|max:12|regex:/\A[a-zA-Z0-9]+\z/']);
        if($validator->fails())
        {
            $result = -1;
        }
        else
        {
            $validated = $validator->safe();
        }

        $user_name = $request->un;

        // クロージャ　関数を引数として渡すようなイメージの仕組み
       DB::transaction(function() use(&$result,$user_id,$user_name,$userData,&$manage_id)
       {
            if($result < 0) {return;}

            // ログ関連
            $log_category = 0;
            $log_context = '';

           // 生成したIDが重複していないかのチェックを入れる、生成できなかったら再度生成、できたら進む
           $checks = User::select('user_id')->get();
           foreach($checks as $check)
           {
               if($user_id == $check)
               {
                   $user_id = Str::ulid(); // idの再生成
                }
            }

            // ユーザーデータ登録
            $usersData = User::create([
                'user_id'=>$user_id,
                'user_name'=>$user_name,
                'handover_passhash'=>config('constants.HANDOVER_PASSHASH'),
                'has_reinforce_point'=>config('constants.HAS_REINFORCE_POINT'),
                'user_rank'=>config('constants.USER_RANK'),
                'login_days'=>config('constants.LOGIN_DAYS'),
                'max_stamina'=>config('constants.MAX_STAMINA'),
                'last_stamina'=>config('constants.LAST_STAMINA'),
            ]);

            $userData = User::where('user_id',$user_id)->first(); // ユーザーデータ取得
            $manage_id = $userData->manage_id;
        
            // ログを追加する処理
            $log_category = config('constants.USER_DATA');
            $log_context = config('constants.REGISTRATION_USER').$userData;
            GameUtilService::logCreate($manage_id,$log_category,$log_context);

            // ウォレットの登録
            $walletsData = UserWallet::create([
                'manage_id'=>$manage_id,
                'free_amount'=>config('constants.FREE_AMOUNT'),
                'paid_amount'=>config('constants.PAID_AMOUNT'),
                'max_amount'=>config('constants.MAX_AMOUNT'),
            ]);

            // ログを追加する処理
            $walletsData = UserWallet::where('manage_id',$manage_id)->first();
            $log_category = config('constants.CURRENCY_DATA');
            $log_context = config('constants.REGISTRATION_WALLET').$walletsData;
            GameUtilService::logCreate($manage_id,$log_category,$log_context);

            // アイテムの登録
            $item_data_list = Item::all();
            foreach($item_data_list as $item_data)
            {
                $checkManageId = ItemInstance::where('manage_id',$manage_id)->where('item_id',$item_data['item_id'])->first();
                if($checkManageId == null)
                {
                    $itemsData = ItemInstance::create([
                    'manage_id'=>$userData->manage_id,
                    'item_id'=>$item_data['item_id'],
                    'item_num'=>config('constants.ITEM_NUM'),
                    'used_num'=>config('constants.USED_NUM'),
                    ]);

                }
            }

            // ログを追加する処理
            $itemData = ItemInstance::where('manage_id',$manage_id)->get();
            $log_category = config('constants.ITEM_DATA');
            $log_context = config('constants.REGISTRATION_Item').$itemData;
            GameUtilService::logCreate($manage_id,$log_category,$log_context);

            $result = 1;
        });

        switch($result)
        {
            case -1:
                $errmsg = config('constants.INCORRECT_STRING');
                $response = $errmsg;
                break;
            case 0:
                $errmsg = config('constants.CANT_REGISTRATION');
                $response = $errmsg;
                break;
            case 1:
                $response = [
                    'users' => User::where('manage_id',$manage_id)->first(),
                    'wallets' => UserWallet::where('manage_id',$manage_id)->first(),
                    'items' => ItemInstance::where('manage_id',$manage_id)->get(),
                ];
                break;
        }

        return json_encode($response);
    }
}
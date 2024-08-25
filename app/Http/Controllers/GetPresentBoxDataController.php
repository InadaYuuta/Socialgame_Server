<?php

namespace App\Http\Controllers;

use App\Libs\PresentBoxUtilService;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\PresentBoxInstance;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GetPresentBoxDataController extends Controller
{
    /* プレゼントボックスデータの取得、マスターに新規プレゼントがあれば作成
    /* uid = ユーザーID
    */ 
    public function __invoke(Request $request)
    {
        $result = 0;
        $errcode = '';
        $response = [];
        
       // ユーザー情報
       $userBase = User::where('user_id',$request->uid);
       // ユーザー情報取得
       $userData = $userBase->first();

       //Auth::login($userData); // TODO: これは仮修正、本来ならログインが継続してこの下に入るはずだけど、なぜか継続されないので一旦ここでログイン
       // --- Auth処理(ログイン確認)-----------------------------------------
       // ユーザーがログインしていなかったらリダイレクト
       if (!Auth::hasUser()) {
           $response = [
               'errcode' => config('constants.ERRCODE_LOGIN_USER_NOT_FOUND'),
           ];
           return json_encode($response);
       }

       $authUserData = Auth::user();
      
       // ユーザー管理ID
       $manage_id = $userData->manage_id;

       // ログインしているユーザーが自分と違ったらリダイレクト
       //if ($manage_id != $authUserData->getAuthIdentifier()) {
       if ($manage_id != $authUserData->manage_id) {
           $response = [
               'errcode' => config('constants.ERRCODE_LOGIN_SESSION'),
           ];
           return json_encode($response);
       }
       // -----------------------------------------------------------------

        $whole_present_box_data = DB::table('whole_present_boxes')->get();

        $distribution_data = []; // 配布可能なデータ一覧

        // 配布可能なデータのみを取得
        foreach($whole_present_box_data as $whole_data)
        {
            $distribution_end = $whole_data->distribution_end; // 配布終了日時
            $current_date = Carbon::now()->format('Y-m-d H:i:s'); // 現在の日時
            if($distribution_end > $current_date)
            {
                array_push($distribution_data,$whole_data);
            }
        }

        // エラーチェック
        if($distribution_data == null)
        {
            $can_receipt_present_data = PresentBoxInstance::where('manage_id',$manage_id)->get();
                $response = [
                    'present_box' => $can_receipt_present_data,
                ];
            return json_encode($response);
        }

        // プレゼントボックスに追加するデータの作成
        DB::transaction(function() use(&$result,$manage_id,$distribution_data){
            foreach($distribution_data as $data)
            {
                // ユーザーごとにプレゼントIdを0から作成
                // そのユーザーのプレゼントIdの最大値を取得
                $present_id = PresentBoxInstance::where('manage_id',$manage_id)->max('present_id');
                $check = PresentBoxInstance::where('manage_id',$manage_id)->where('present_id',0)->first();
                if($check == null)
                {
                    $present_id = 0;
                }
                else
                {
                    $present_id += 1;
                }

                $whole_present_id = $data->whole_present_id;
                
                // 重複しているかを確認する、重複していたら無視して次に
                $check = PresentBoxInstance::where('manage_id',$manage_id)->where('present_id',$present_id)->first();
                if($check != null){continue;}
                $check = PresentBoxInstance::where('manage_id',$manage_id)->where('whole_present_id',$whole_present_id)->first();
                if($check != null){continue;}
                 // プレゼント情報
                $presentData = [
                    'manage_id'=>$manage_id,
                    'present_id'=>$present_id,
                    'whole_present_id'=>$whole_present_id,
                    'category'=>$data->reward_category,
                    'reward'=>$data->present_box_reward,
                    'reason'=>$data->receive_reason,
                    'display'=>$data->distribution_end,
                ];
                PresentBoxInstance::create([
                    'manage_id'=>$presentData['manage_id'],
                    'present_id'=>$presentData['present_id'],
                    'whole_present_id'=>$presentData['whole_present_id'],
                    'reward_category'=>$presentData['category'],
                    'present_box_reward'=>$presentData['reward'],
                    'receive_reason'=>$presentData['reason'],
                    'display'=>$presentData['display'],
                ]);
            }
            $result = 1;
        });

        switch($result)
        {
            case 0:
                $response = 0;
                break;
            case 1:
                $can_receipt_present_data = PresentBoxInstance::where('manage_id',$manage_id)->get();
                $response = [
                    'present_box' => $can_receipt_present_data,
                ];
                break;
        }

        return json_encode($response);
    }
}

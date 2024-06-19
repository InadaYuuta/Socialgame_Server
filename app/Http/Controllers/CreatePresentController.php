<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\PresentBoxInstance;

use Illuminate\Support\Facades\DB;

class CreatePresentController extends Controller
{
    /* プレゼント作成
    /* uid = ユーザーID
    /* rCategory = プレゼントのカテゴリー
    /* reward = プレゼントの報酬
    /* reason = プレゼントが届いた理由 
    */ 
    public function __invoke(Request $request)
    {
        $result = 0;
        $errcode = '';
        $response = [];

        // ユーザー情報
        $userData = User::where('user_id',$request->uid)->first();

        // ユーザー管理ID
        $manage_id = $userData->manage_id;
        
        $present_id = DB::table('present_box_instances')->max('present_id');

        $check = PresentBoxInstance::where('present_id',0)->first();
        if($check == null)
        {
            $present_id = 0;
        }
        else
        {
            $present_id += 1;
        }

        // プレゼント情報
        $presentData = [
            'manage_id'=>$manage_id,
            'present_id'=>$present_id,
            'category'=>$request->rCategory,
            'reward'=>$request->reward,
            'reason'=>$request->reason,
        ];

        DB::transaction(function() use(&$result,$presentData){
            PresentBoxInstance::create([
                'manage_id'=>$presentData['manage_id'],
                'present_id'=>$presentData['present_id'],
                'reward_category'=>$presentData['category'],
                'present_box_reward'=>$presentData['reward'],
                'receive_reason'=>$presentData['reason'],
            ]);
            $result = 1;
        });

        switch($result)
        {
            case 0:
                $errcode = config('constants.ERRCODE_CAN_NOT_ADD_PRESENT');
                $response = [
                    'errcode' => $errcode,
                ];
                break;
            case 1:
                $response = [
                    'present_box' => PresentBoxInstance::where('manage_id',$manage_id)->get(),
                ];
                break;
        }

        return json_encode($response);
    }
}

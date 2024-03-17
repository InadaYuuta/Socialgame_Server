<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\PrezentBoxInstance;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CreatePrezentController extends Controller
{
    /* プレゼント作成(ユーザーは操作しない) 
    /* uid = ユーザーID
    /* rCategory = プレゼントのカテゴリー
    /* reward = プレゼントの報酬
    /* reason = プレゼントが届いた理由 
    */ 
    public function __invoke(Request $request)
    {
        $result = 0;
        $errmsg = '';
        $response = [];

        // ユーザー情報
        $userData = User::where('user_id',$request->uid)->first();

        // ユーザー管理ID
        $manage_id = $userData->manage_id;
        
        $prezent_id = DB::table('prezent_box_instances')->max('prezent_id');

        $check = PrezentBoxInstance::where('prezent_id',0)->first();
        if($check == null)
        {
            $prezent_id = 0;
        }
        else
        {
            $prezent_id += 1;
        }

        // プレゼント情報
        $prezentData = [
            'manage_id'=>$manage_id,
            'prezent_id'=>$prezent_id,
            'category'=>$request->rCategory,
            'reward'=>$request->reward,
            'reson'=>$request->reason,
        ];

        DB::transaction(function() use(&$result,$prezentData){
            PrezentBoxInstance::create([
                'manage_id'=>$prezentData['manage_id'],
                'prezent_id'=>$prezentData['prezent_id'],
                'reward_category'=>$prezentData['category'],
                'prezent_box_reward'=>$prezentData['reward'],
                'receive_reson'=>$prezentData['reson'],
            ]);
            $result = 1;
        });

        switch($result)
        {
            case 0:
                $errmsg = config('constants.CANT_ADD_PREZENT');
                $response = [
                    'errmsg' => $errmsg,
                ];
                break;
            case 1:
                $response = [
                    'prezent_box' => PrezentBoxInstance::where('manage_id',$manage_id)->get(),
                ];
                break;
        }

        return json_encode($response);
    }
}

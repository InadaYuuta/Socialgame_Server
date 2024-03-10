<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use App\Models\PrezentBoxInstance;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CreatePrezentController extends Controller
{
    public function __invoke(Request $request)
    {
        // ユーザー情報
        $userData = Users::where('user_id',$request->uid)->first();
        
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
            'manage_id'=>$userData->manage_id,
            'prezent_id'=>$prezent_id,
            'category'=>$request->rCategory,
            'reward'=>$request->reward,
            'reson'=>$request->reason,
        ];

        DB::transaction(function() use($prezentData){
            PrezentBoxInstance::create([
                'manage_id'=>$prezentData['manage_id'],
                'prezent_id'=>$prezentData['prezent_id'],
                'reward_category'=>$prezentData['category'],
                'prezent_box_reward'=>$prezentData['reward'],
                'receive_reson'=>$prezentData['reson'],
            ]);
        });

        $response = [
            'prezent_box'=>PrezentBoxInstance::where('manage_id',$userData->manage_id)->get(),
        ];

        return json_encode($response);
    }
}

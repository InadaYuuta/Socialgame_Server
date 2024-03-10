<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Users;
use App\Models\Mission;
use App\Models\MissionCategory;
use App\Models\MissionInstance;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CreateMissionController extends Controller
{
    // uid = ユーザーID
    // mid = ミッションID
    public function __invoke(Request $request)
    {
        $result = 0;
        $errmsg = '';
        $response = 0;

        // ユーザー情報
        $userData = Users::where('user_id',$request->uid)->first();

        // 管理ID
        $manage_id = $userData->manage_id;

        // ミッション情報
        $missionData = Mission::where('mission_id',$request->mid)->first();

        // ミッションインスタンスのもと
        $instanceBase = MissionInstance::where('manage_id',$manage_id)->where('mission_id',$missionData->mission_id);

        // ミッション生成
        DB::transaction(function() use($manage_id,$missionData,$instanceBase,&$result){
            // 最初だけ一括で生成(1010001~1050001まで)
            $check = MissionInstance::where('manage_id',$manage_id)->where('mission_id',1010001)->first();
            if($check == null)
            {
                $first_list = [
                    [
                        'mission_id' => 1010001,
                    ],
                    [
                        'mission_id' => 1020001,
                    ],
                    [
                        'mission_id' => 1030001,
                    ],
                    [
                        'mission_id' => 1040001,
                    ],
                    [
                        'mission_id' => 1050001,
                    ],
                ];
    
                foreach($first_list as $data)
                {
                    $check = MissionInstance::where('manage_id',$manage_id)->where('mission_id',$data['mission_id'])->first();
                    if($check == null)
                    {
                        $instanceData = Mission::where('mission_id',$data['mission_id'])->first();
                        $result = MissionInstance::create([
                            'manage_id'=>$manage_id,
                            'mission_id'=>$instanceData->mission_id,
                            'term' => $instanceData->period_end,
                        ]);
                        $result = 1;
                    }
                    else
                    {
                        $result = -1;
                    }
                }
            }
            // 最初以外
            else
            {
                $missionInstanceData = $instanceBase->first();
                if($missionInstanceData == null)
                {
                    $missionInstanceData = MissionInstance::create([
                        'manage_id'=>$manage_id,
                        'mission_id'=>$missionData->mission_id,
                        'term' => $missionData->period_end,
                    ]);
                    $result = 1;
                }
                else
                {
                    $result = -1;
                }
            }
        });

        switch($result)
        {
            case -1:
                $errmsg = '既に追加済みのミッションです';
                $response = $errmsg;
                break;
            case 0:
                $errmsg = '追加できませんでした';
                $response = $errmsg;
                break;
            case 1:
                $response = [
                    'missions' => MissionInstance::where('manage_id',$manage_id)->get(),
                ];
                break;
        }

        return json_encode($response);
    }
}

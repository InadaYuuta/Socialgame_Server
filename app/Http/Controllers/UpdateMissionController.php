<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Users;
use App\Models\Mission;
use App\Models\MissionCategory;
use App\Models\MissionInstance;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

// uid = ユーザーID
// mid = ミッションID
// prog = 進捗
class UpdateMissionController extends Controller
{
    public function __invoke(Request $request)
    {
        $result = 0;
        $errmsg = '';
        $response = 0;

        // ユーザー情報
        $userData = Users::where('user_id',$request->uid)->first();

        // 管理ID
        $manage_id = $userData->manage_id;

        // 更新するミッションのマスター情報
        $missionData = Mission::where('mission_id',$request->mid)->first();

        // 達成条件(目標値)
        $achieved_condition = Str::after($missionData->achievement_condition,'/');

        // ミッションインスタンスのもと
        $missionInstanceBase = MissionInstance::where('manage_id',$manage_id)->where('mission_id',$missionData->mission_id);

        // 進捗
        $progress = $request->prog;

        DB::transaction(function() use($manage_id,$missionData,$missionInstanceBase,$achieved_condition,$progress,&$result){
            $instanceData = $missionInstanceBase->first();
            $achieved = $instanceData->achieved;
            // 達成していなければ進捗更新
            if($achieved == 0)
            {
                $result = $missionInstanceBase->update([
                    'progress' => $progress,
                ]);
                // 進捗が目標値に到達していたら達成に
                if($progress >= $achieved_condition)
                {
                    $result = $missionInstanceBase->update([
                        'achieved' => 1,
                        'validity_term'=> Carbon::now()->format('Y-m-d H:i:s'),
                    ]);
                    // 次のミッションがある場合は次のミッションを作成する
                    $next_mission_id = $missionData->next_mission_id;
                    $missionInstanceData = MissionInstance::where('manage_id',$manage_id)->where('mission_id',$next_mission_id)->first();
                    if($missionInstanceData == null)
                    {
                        $missionInstanceData = MissionInstance::create([
                            'manage_id'=>$manage_id,
                            'mission_id'=>$next_mission_id,
                            'term' => $missionData->period_end,
                        ]);
                        $result = 1;
                    }
                    else
                    {
                        $result = -1;
                    }
                }
                $result = 1;
            }
            else
            {
                $result = -1;
                // 次のミッションがある場合は次のミッションを作成する
                $next_mission_id = $missionData->next_mission_id;
                $missionInstanceData = MissionInstance::where('manage_id',$manage_id)->where('mission_id',$next_mission_id)->first();
                if($missionInstanceData == null)
                {
                    $missionInstanceData = MissionInstance::create([
                        'manage_id'=>$manage_id,
                        'mission_id'=>$next_mission_id,
                        'term' => $missionData->period_end,
                    ]);
                    $result = 1;
                }
                else
                {
                    $result = -2;
                }
            }
        });

        switch($result)
        {
            case -1:
                $errmsg = '達成済みのミッションです';
                $response = $errmsg;
                break;
            case -2:
                $errmsg = '追加済みのミッションです';
                $response = $errmsg;
                break;
            case 0:
                $errmsg = '更新できませんでした';
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

<?php
namespace App\Libs;

use App\Models\Log;
use App\Models\Mission;
use App\Models\MissionInstance;
use Illuminate\Support\Facades\Auth;

class MissionUtilService
{
    /*初めてミッションを作成するときに、指定のミッションを一括で生成
     * $result=呼び出すコードのresult $manage_id = 呼び出すユーザーのマネージID
     */
    public static function firstCreateMission(&$result,$manage_id)
    {
        // 最初だけ一括で生成(1010001~1050001まで)
        $check = MissionInstance::where('manage_id',$manage_id)->where('mission_id',1010001)->first();
        if($check == null)
        {
            // 最初だけ一括で生成(1010001~1050001まで)
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
                }
                else
                {
                    continue;
                }
            }
        }
    }

    /* ミッションを達成したときに次のミッションがあればそれを作成する
     * $manage_id = プレイヤーのmanage_id
     * $achieveMissionData = 達成したミッションのデータ
     */
    public static function createNextMission($manage_id,$achieveMissionData)
    {
       $next_mission_id = $achieveMissionData->next_mission_id;
       // 次のミッションが存在するかどうかチェック、あれば次のミッションを作成
       if($next_mission_id == null)
    {
        return config('constants.ERRCODE_NEXT_MISSION_DOES_NOT_EXITS'); /* TODO:ここはエラーコードにする*/
    }

       $createMissionData = Mission::where('mission_id',$next_mission_id)->first();

       // 新しいミッションを作成する
       $missionInstanceData = MissionInstance::create([
        'manage_id' => $manage_id,
        'mission_id' => $next_mission_id,
        'term' => $createMissionData->period_end,
       ]);
    }

    
}
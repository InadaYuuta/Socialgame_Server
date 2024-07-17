<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\MissionUtilService;

use App\Models\User;
use App\Models\Mission;
use App\Models\MissionInstance;

use Illuminate\Support\Facades\DB;

class CreateMissionController extends Controller
{
    /* ミッション作成(ユーザーは操作しない) 
    /* uid = ユーザーID
    /* mid = ミッションID
    */
    public function __invoke(Request $request)
    {
        $result = 0;
        $errcode = '';
        $response = [];

        // ユーザー情報
        $userData = User::where('user_id',$request->uid)->first();

        // 管理ID
        $manage_id = $userData->manage_id;

        // ミッション情報
        $missionData = Mission::where('mission_id',$request->mid)->first();

        // ミッションインスタンスのもと
        $instanceBase = MissionInstance::where('manage_id',$manage_id)->where('mission_id',$missionData->mission_id);

        // エラーチェック
        $missionInstanceData = $instanceBase->first();
        if($missionInstanceData != null)
        {
            $errcode = config('constants.ERRCODE_MISSION_ALREADY_ADDED');
                $response = [
                    'errcode' => $errcode,
                ];
            return json_encode($response);
        }

        // ミッション生成
        DB::transaction(function() use(&$result,$manage_id,$missionData,$missionInstanceData){
            if($missionInstanceData == null)
            {
                $missionInstanceData = MissionInstance::create([
                    'manage_id'=>$manage_id,
                    'mission_id'=>$missionData->mission_id,
                    'term' => $missionData->period_end,
                ]);
                $result = 1;
            }
        });

        switch($result)
        {
            case 0:
                $errcode = config('constants.ERRCODE_CANT_ADD_MISSION');
                $response = [
                    'errcode' => $errcode,
                ];
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

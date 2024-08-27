<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\GameUtilService;

use App\Models\User;
use App\Models\Mission;
use App\Models\MissionInstance;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


/* ミッション更新
/* uid = ユーザーID
/* mid = ミッションID
/* prog = 進捗
*/
class UpdateMissionController extends Controller
{
    public function __invoke(Request $request)
    {
        $result = 0;
        $errcode = '';
        $response = 0;

        // --- Auth処理(ログイン確認)-----------------------------------------
        // ユーザーがログインしていなかったらリダイレクト
        if (!Auth::hasUser()) {
            $response = [
                'errcode' => config('constants.ERRCODE_LOGIN_USER_NOT_FOUND'),
            ];
            return json_encode($response);
        }

        $authUserData = Auth::user();

        // ユーザー情報
        $userBase = User::where('user_id',$request->uid);

        // ユーザー情報取得
        $userData = $userBase->first();
       
        // ユーザー管理ID
        $manage_id = $userData->manage_id;

        // ログインしているユーザーが自分と違ったらリダイレクト
        if ($manage_id != $authUserData->manage_id) {
            $response = [
                'errcode' => config('constants.ERRCODE_LOGIN_SESSION'),
            ];
            return json_encode($response);
        }
        // -----------------------------------------------------------------
        // 更新するミッションのマスター情報
        $missionData = Mission::where('mission_id',$request->mid)->first();

        // 達成条件(目標値)
        $achieved_condition = Str::after($missionData->achievement_condition,'/');

        // ミッションインスタンスのもと
        $missionInstanceBase = MissionInstance::where('manage_id',$manage_id)->where('mission_id',$missionData->mission_id);

        // 進捗
        $progress = $request->prog;


        // エラーチェック 
        $instanceData = $missionInstanceBase->first(); // ミッションデータ
        $achieved = $instanceData->achieved; // 達成しているか
        if($achieved > 0)
        {
            $response = [
                'missions' => MissionInstance::where('manage_id',$manage_id)->get(),
            ];
            return json_encode($response); // 達成していたら更新せずに帰す
        }
        
        // 達成していなければ進捗更新
        DB::transaction(function() use($manage_id,$missionData,$missionInstanceBase,$achieved_condition,$progress,&$result){

            // ログ関連
            $log_category = 0;
            $log_context = '';

            $instanceData = $missionInstanceBase->first(); // ミッションデータ
            $achieved = $instanceData->achieved; // 達成しているか
            // 達成していなければ進捗更新
            if($achieved == 0)
            {
                $result = $missionInstanceBase->update([
                    'progress' => $progress,
                ]);

                // ログを追加する処理(ミッション進捗更新)
                $log_category = config('constants.MISSION_DATA');
                $log_context = config('constants.PROGRESS_MISSION').$progress.'/'.$instanceData;
                GameUtilService::logCreate($manage_id,$log_category,$log_context);

                // 進捗が目標値に到達していたら達成に
                if($progress >= $achieved_condition)
                {
                    $result = $missionInstanceBase->update([
                        'achieved' => 1,
                        'validity_term'=> Carbon::now()->format('Y-m-d H:i:s'),
                    ]);

                    // ログを追加する処理(ミッション達成更新)
                    $instanceData = $missionInstanceBase->first(); // ミッションデータ
                    $log_category = config('constants.MISSION_DATA');
                    $log_context = config('constants.ACHIEVED_MISSION').$instanceData;
                    GameUtilService::logCreate($manage_id,$log_category,$log_context);

                    // 次のミッションがある場合は次のミッションを作成する
                    $next_mission_id = $missionData->next_mission_id;
                    $missionInstanceData = MissionInstance::where('manage_id',$manage_id)->where('mission_id',$next_mission_id)->first();
                    $check = Mission::where('mission_id',$next_mission_id)->first(); // マスタデータに次のミッションが存在しているかどうかを確認
                    if($missionInstanceData == null && $check != null)
                    {
                        $missionInstanceData = MissionInstance::create([
                            'manage_id'=>$manage_id,
                            'mission_id'=>$next_mission_id,
                            'term' => $missionData->period_end,
                        ]);
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
                }
            }
        });

        switch($result)
        {
            case 0:
                $errcode = config('constants.CANT_UPDATE_MISSION');
                $response = $errcode;
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

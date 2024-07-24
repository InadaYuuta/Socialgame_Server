<?php

namespace App\Libs;

use App\Models\PresentBoxInstance;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PresentBoxUtilService
{
    /*現在受取可能かつ表示期間内のプレゼントボックスインスタンスのデータを返す */
    public static function GetCanReceiptPresentBoxData($manage_id)
    {
        $can_receipt_present_data = [];
        $check_present_data = PresentBoxInstance::where('manage_id',$manage_id)->get();
        $current_date = Carbon::now()->format('Y-m-d H:i:s'); // 現在の日時
        foreach($check_present_data as $data)
        {
           if($data->receipt != 1 && $data->display > $current_date)
            {
                array_push($can_receipt_present_data,$data); 
            }
        }
        return $can_receipt_present_data;
    }

    /**
     * TODO 今後時間があれば他の要因でもプレゼントを作成できるようにする、さらに言えばCreatePresentControllerの方もこれ使うように改良するといいかも
     * ミッション報酬がいっぱいだったらプレゼントを作成する
     * *$manage_id = ユーザーのmanage_id
     * *$category = プレゼントのカテゴリ
     * *$rewardNum = プレゼントの量
     */
    public static function CreatePresent($manage_id,$category,$rewardNum):int
    {
        $result = 0;

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
            'category'=>$category,
            'reward'=>$rewardNum,
            'reason'=>"所持数上限により受け取れなかったミッション報酬です。",
        ];

        // エラーチェック
        $check = PresentBoxInstance::where('manage_id',$manage_id)->where('present_id',$present_id)->first();
        if($check != null)
        {
            $errcode = config('constants.ERRCODE_PRESENT_ALREADY_ADDED');
            $response = $errcode;
            return json_encode($response);
        }

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

        return $result;
    }
}
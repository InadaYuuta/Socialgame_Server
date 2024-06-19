<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\WholePresentBox;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CreateWholePresentController extends Controller
{
    /* 全体プレゼント作成(ユーザーは操作しない) 
    /* rCategory = プレゼントのカテゴリー
    /* reward = プレゼントの報酬
    /* reason = プレゼントが届いた理由 
    /* start = 配布開始日時
    /* end = 配布終了日時
    */ 
    // TODO: 毎回手動入力は大変だから、サイトを作って楽に追加できるようにしたい
    public function __invoke(Request $request)
    {
        $result = 0;
        $errcode = '';
        $response = [];
        $category = $request->rCategory;
        $reward = $request->reward;
        $reason = $request->reason;
        $start = $request->start;
        $end = $request->end;
        if($category == null || $reward == null || $reason == null||$start == null || $end == null)
        {
            dd("指定忘れがあります");
        }

        // 追加するプレゼント情報
        $presentData = [
            'category'=>$category,
            'reward'=>$reward,
            'reason'=>$reason,
            'distribution_start'=>$start,
            'distribution_end'=>$end,
        ];

        DB::transaction(function() use(&$result,$presentData){
           WholePresentBox::create([
            'reward_category' => $presentData['category'],
            'present_box_reward' => $presentData['reward'],
            'receive_reason' => $presentData['reason'],
            'distribution_start' => $presentData['distribution_start'],
            'distribution_end' => $presentData['distribution_end'],
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
                    'whole_present' => WholePresentBox::get(),
                ];
                break;
        }

        return json_encode($response);
    }
}

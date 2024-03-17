<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Mission;
use App\Models\MissionCategory;
use App\Models\MissionInstance;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AddMissionMasterDataController extends Controller
{
    /* ミッション関連のマスターデータを追加する TODO:ここはできたらRequestを使ってまとめて外部から追加できるようにしたい */
    public function __invoke()
    {
        // ミッションカテゴリー
        $addMissionCategoryData = [
            [
                'mission_category' => 1,
                'category_name'=>'CONSTANCY', // 恒常
            ],
            [
                'mission_category' => 2,
                'category_name'=>'DAILY', // デイリー
            ],
            [
                'mission_category' => 3,
                'category_name'=>'WEEKLY', // ウィークリー
            ],
            [
                'mission_category' => 4,
                'category_name'=>'LIMITEDPERIOD', // 期間限定
            ],
        ];

        // ミッションマスター
        $addMissionData = [
            [
                'mission_id'=>1010001,
                'next_mission_id'=>1010002,
                'mission_name'=>'ガチャを引こう(1)',
                'mission_content'=>'ガチャを累計1回引く',
                'mission_category'=>1,
                'reward_category'=>1,
                'mission_reward'=>'payment/10',
                'achievement_condition'=>'gacha/1',
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            [
                'mission_id'=>1020001,
                'next_mission_id'=>1020002,
                'mission_name'=>'ログインしよう(1)',
                'mission_content'=>'累計1日ログインする',
                'mission_category'=>1,
                'reward_category'=>1,
                'mission_reward'=>'payment/30',
                'achievement_condition'=>'login/1',
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            [
                'mission_id'=>1030001,	
                'next_mission_id'=>1030002,
                'mission_name'=>'武器を獲得しよう(1)',
                'mission_content'=>'武器を累計1つ獲得する',
                'mission_category'=>1,
                'reward_category'=>1,
                'mission_reward'=>'payment/10',
                'achievement_condition'=>'weaponGet/1',
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            [
                'mission_id'=>1040001,
                'next_mission_id'=>1040002,
                'mission_name'=>'武器を強化しよう(1)',
                'mission_content'=>'武器を累計1つ強化する',
                'mission_category'=>1,
                'reward_category'=>1,
                'mission_reward'=>'payment/10',
                'achievement_condition'=>'weaponReinforce/1',
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            [
                'mission_id'=>1050001,
                'next_mission_id'=>1050002,
                'mission_name'=>'武器を進化しよう(1)',
                'mission_content'=>'武器を累計1つ進化する',
                'mission_category'=>1,
                'reward_category'=>1,
                'mission_reward'=>'payment/10',
                'achievement_condition'=>'weaponEvolution/1',
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            [
                'mission_id'=>1010002,
                'next_mission_id'=>1010003,
                'mission_name'=>'ガチャを引こう(2)',
                'mission_content'=>'ガチャを累計10回引く',
                'mission_category'=>1,
                'reward_category'=>1,
                'mission_reward'=>'payment/10',
                'achievement_condition'=>'gacha/10',
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            [
                'mission_id'=>1020002,
                'next_mission_id'=>1020003,
                'mission_name'=>'ログインしよう(2)',
                'mission_content'=>'累計2日ログインする',
                'mission_category'=>1,
                'reward_category'=>1,
                'mission_reward'=>'payment/30',
                'achievement_condition'=>'login/2',
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            [
                'mission_id'=>1030002,	
                'next_mission_id'=>1030003,
                'mission_name'=>'武器を獲得しよう(2)',
                'mission_content'=>'武器を累計3つ獲得する',
                'mission_category'=>1,
                'reward_category'=>1,
                'mission_reward'=>'payment/10',
                'achievement_condition'=>'weaponGet/3',
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            [
                'mission_id'=>1040002,
                'next_mission_id'=>1040003,
                'mission_name'=>'武器を強化しよう(2)',
                'mission_content'=>'武器を累計3つ強化する',
                'mission_category'=>1,
                'reward_category'=>1,
                'mission_reward'=>'payment/10',
                'achievement_condition'=>'weaponReinforce/3',
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            [
                'mission_id'=>1050002,
                'next_mission_id'=>1050003,
                'mission_name'=>'武器を進化しよう(2)',
                'mission_content'=>'武器を累計3つ進化する',
                'mission_category'=>1,
                'reward_category'=>1,
                'mission_reward'=>'payment/10',
                'achievement_condition'=>'weaponEvolution/3',
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
        ];

        DB::transaction(function() use ($addMissionCategoryData,$addMissionData){
            // ミッションカテゴリー
            foreach($addMissionCategoryData as $data)
            {
                $check = MissionCategory::where('mission_category',$data['mission_category'])->first();
                if($check == null)
                {
                    MissionCategory::create([
                        'mission_category'=>$data['mission_category'],
                        'category_name'=>$data['category_name'],
                    ]);
                }
            }
            // ミッションマスター
            foreach($addMissionData as $data)
            {
                $check = Mission::where('mission_id',$data['mission_id'])->first();
                if($check == null)
                {
                    Mission::create([
                        'mission_id'=>$data['mission_id'],
                        'next_mission_id'=>$data['next_mission_id'],
                        'mission_name'=>$data['mission_name'],
                        'mission_content'=>$data['mission_content'],
                        'mission_category'=>$data['mission_category'],
                        'reward_category'=>$data['reward_category'],
                        'mission_reward'=>$data['mission_reward'],
                        'achievement_condition'=>$data['achievement_condition'],
                        'period_start'=>$data['period_start'],
                        'period_end'=>$data['period_end'],
                    ]);
                }
            }
        });
    }
}
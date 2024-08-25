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
                'category_name'=>'LIMITED_PERIOD', // 期間限定
            ],
        ];

        // ミッションマスター(とりあえず恒常のみ、準備ができたらデイリーとウィークリーも追加する)
        $addMissionData = [
            // ガチャ回数
            [
                'mission_id'=>1010001,
                'next_mission_id'=>1010002,
                'mission_name'=>'ガチャを引こう(1)',
                'mission_content'=>'ガチャを累計10回引く',
                'mission_category'=>1,
                'reward_category'=>1,
                'mission_reward'=>'payment/10',
                'achievement_condition'=>'gacha/10',
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            [
                'mission_id'=>1010002,
                'next_mission_id'=>1010003,
                'mission_name'=>'ガチャを引こう(2)',
                'mission_content'=>'ガチャを累計20回引く',
                'mission_category'=>1,
                'reward_category'=>1,
                'mission_reward'=>'payment/10',
                'achievement_condition'=>'gacha/20',
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            [
                'mission_id'=>1010003,	
                'next_mission_id'=>1010004,
                'mission_name'=>'ガチャを引こう(3)',
                'mission_content'=>'ガチャを累計30回引く',
                'mission_category'=>1,
                'reward_category'=>1,
                'mission_reward'=>'payment/10',
                'achievement_condition'=>'gacha/30',
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            [
                'mission_id'=>1010004,
                'next_mission_id'=>1010005,
                'mission_name'=>'ガチャを引こう(4)',
                'mission_content'=>'ガチャを累計40回引く',
                'mission_category'=>1,
                'reward_category'=>1,
                'mission_reward'=>'payment/10',
                'achievement_condition'=>'gacha/40',
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            [
                'mission_id'=>1010005,
                'next_mission_id'=>1010006,
                'mission_name'=>'ガチャを引こう(5)',
                'mission_content'=>'ガチャを累計50回引く',
                'mission_category'=>1,
                'reward_category'=>1,
                'mission_reward'=>'payment/10',
                'achievement_condition'=>'gacha/50',
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            [
                'mission_id'=>1010006,
                'next_mission_id'=>1010007,
                'mission_name'=>'ガチャを引こう(6)',
                'mission_content'=>'ガチャを累計60回引く',
                'mission_category'=>1,
                'reward_category'=>1,
                'mission_reward'=>'payment/10',
                'achievement_condition'=>'gacha/60',
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            // ログイン日数
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
                'mission_id'=>1020003,	
                'next_mission_id'=>1020004,
                'mission_name'=>'ログインしよう(3)',
                'mission_content'=>'累計3日ログインする',
                'mission_category'=>1,
                'reward_category'=>1,
                'mission_reward'=>'payment/60',
                'achievement_condition'=>'login/3',
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            [
                'mission_id'=>1020004,
                'next_mission_id'=>1020005,
                'mission_name'=>'ログインしよう(4)',
                'mission_content'=>'累計4日ログインする',
                'mission_category'=>1,
                'reward_category'=>1,
                'mission_reward'=>'payment/30',
                'achievement_condition'=>'login/4',
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            [
                'mission_id'=>1020005,
                'next_mission_id'=>1020006,
                'mission_name'=>'ログインしよう(5)',
                'mission_content'=>'累計5日ログインする',
                'mission_category'=>1,
                'reward_category'=>1,
                'mission_reward'=>'payment/60',
                'achievement_condition'=>'login/5',
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            [
                'mission_id'=>1020006,
                'next_mission_id'=>1020007,
                'mission_name'=>'ログインしよう(6)',
                'mission_content'=>'累計6日ログインする',
                'mission_category'=>1,
                'reward_category'=>1,
                'mission_reward'=>'payment/30',
                'achievement_condition'=>'login/6',
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            // 武器獲得数
            [
                'mission_id'=>1030001,
                'next_mission_id'=>1030002,
                'mission_name'=>'武器を獲得しよう(1)',
                'mission_content'=>'武器を累計1本獲得する',
                'mission_category'=>1,
                'reward_category'=>1,
                'mission_reward'=>'payment/10',
                'achievement_condition'=>'weaponGet/1',
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            [
                'mission_id'=>1030002,
                'next_mission_id'=>1030003,
                'mission_name'=>'武器を獲得しよう(2)',
                'mission_content'=>'武器を累計2本獲得する',
                'mission_category'=>1,
                'reward_category'=>1,
                'mission_reward'=>'payment/10',
                'achievement_condition'=>'weaponGet/2',
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            [
                'mission_id'=>1030003,	
                'next_mission_id'=>1030004,
                'mission_name'=>'武器を獲得しよう(3)',
                'mission_content'=>'武器を累計3本獲得する',
                'mission_category'=>1,
                'reward_category'=>1,
                'mission_reward'=>'payment/10',
                'achievement_condition'=>'weaponGet/3',
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            [
                'mission_id'=>1030004,
                'next_mission_id'=>1030005,
                'mission_name'=>'武器を獲得しよう(4)',
                'mission_content'=>'武器を累計5本獲得する',
                'mission_category'=>1,
                'reward_category'=>1,
                'mission_reward'=>'payment/10',
                'achievement_condition'=>'weaponGet/5',
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            [
                'mission_id'=>1030005,
                'next_mission_id'=>1030006,
                'mission_name'=>'武器を獲得しよう(5)',
                'mission_content'=>'武器を累計10本獲得する',
                'mission_category'=>1,
                'reward_category'=>1,
                'mission_reward'=>'payment/10',
                'achievement_condition'=>'weaponGet/10',
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            [
                'mission_id'=>1030006,
                'next_mission_id'=>1030007,
                'mission_name'=>'武器を獲得しよう(6)',
                'mission_content'=>'武器を累計15本獲得する',
                'mission_category'=>1,
                'reward_category'=>1,
                'mission_reward'=>'payment/10',
                'achievement_condition'=>'weaponGet/15',
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            // 武器レベルアップ
            [
                'mission_id'=>1040001,
                'next_mission_id'=>1040002,
                'mission_name'=>'武器をレベルアップしよう(1)',
                'mission_content'=>'武器の合計レベルを10にしよう',
                'mission_category'=>1,
                'reward_category'=>1,
                'mission_reward'=>'payment/10',
                'achievement_condition'=>'weaponLevelUp/10',
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            [
                'mission_id'=>1040002,
                'next_mission_id'=>1040003,
                'mission_name'=>'武器をレベルアップしよう(2)',
                'mission_content'=>'武器の合計レベルを20にしよう',
                'mission_category'=>1,
                'reward_category'=>1,
                'mission_reward'=>'payment/10',
                'achievement_condition'=>'weaponLevelUp/20',
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            [
                'mission_id'=>1040003,	
                'next_mission_id'=>1040004,
                'mission_name'=>'武器をレベルアップしよう(3)',
                'mission_content'=>'武器の合計レベルを30にしよう',
                'mission_category'=>1,
                'reward_category'=>1,
                'mission_reward'=>'payment/10',
                'achievement_condition'=>'weaponLevelUp/30',
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            [
                'mission_id'=>1040004,
                'next_mission_id'=>1040005,
                'mission_name'=>'武器をレベルアップしよう(4)',
                'mission_content'=>'武器の合計レベルを40にしよう',
                'mission_category'=>1,
                'reward_category'=>1,
                'mission_reward'=>'payment/10',
                'achievement_condition'=>'weaponLevelUp/40',
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            [
                'mission_id'=>1040005,
                'next_mission_id'=>1040006,
                'mission_name'=>'武器をレベルアップしよう(5)',
                'mission_content'=>'武器の合計レベルを50にしよう',
                'mission_category'=>1,
                'reward_category'=>1,
                'mission_reward'=>'payment/10',
                'achievement_condition'=>'weaponLevelUp/50',
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            [
                'mission_id'=>1040006,
                'next_mission_id'=>1040007,
                'mission_name'=>'武器をレベルアップしよう(6)',
                'mission_content'=>'武器の合計レベルを60にしよう',
                'mission_category'=>1,
                'reward_category'=>1,
                'mission_reward'=>'payment/10',
                'achievement_condition'=>'weaponLevelUp/60',
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            // 武器進化数
            [
                'mission_id'=>1050001,
                'next_mission_id'=>1050002,
                'mission_name'=>'武器を進化しよう(1)',
                'mission_content'=>'武器を合計1本進化する',
                'mission_category'=>1,
                'reward_category'=>1,
                'mission_reward'=>'payment/10',
                'achievement_condition'=>'weaponEvolution/1',
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            [
                'mission_id'=>1050002,
                'next_mission_id'=>1050003,
                'mission_name'=>'武器を進化しよう(2)',
                'mission_content'=>'武器を合計2本進化する',
                'mission_category'=>1,
                'reward_category'=>1,
                'mission_reward'=>'payment/10',
                'achievement_condition'=>'weaponEvolution/2',
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            [
                'mission_id'=>1050003,	
                'next_mission_id'=>1050004,
                'mission_name'=>'武器を進化しよう(3)',
                'mission_content'=>'武器を合計3本進化する',
                'mission_category'=>1,
                'reward_category'=>1,
                'mission_reward'=>'payment/10',
                'achievement_condition'=>'weaponEvolution/3',
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            [
                'mission_id'=>1050004,
                'next_mission_id'=>1050005,
                'mission_name'=>'武器を進化しよう(4)',
                'mission_content'=>'武器を合計4本進化する',
                'mission_category'=>1,
                'reward_category'=>1,
                'mission_reward'=>'payment/10',
                'achievement_condition'=>'weaponEvolution/4',
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            [
                'mission_id'=>1050005,
                'next_mission_id'=>1050006,
                'mission_name'=>'武器を進化しよう(5)',
                'mission_content'=>'武器を合計5本進化する',
                'mission_category'=>1,
                'reward_category'=>1,
                'mission_reward'=>'payment/10',
                'achievement_condition'=>'weaponEvolution/5',
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            [
                'mission_id'=>1050006,
                'next_mission_id'=>1050007,
                'mission_name'=>'武器を進化しよう(6)',
                'mission_content'=>'武器を合計6本進化する',
                'mission_category'=>1,
                'reward_category'=>1,
                'mission_reward'=>'payment/10',
                'achievement_condition'=>'weaponEvolution/6',
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
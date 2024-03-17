<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\NewsCategory;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AddNewsController extends Controller
{
    /* ニュース関連のマスターデータを追加する TODO:ここはできたらRequestを使ってまとめて外部から追加できるようにしたい */
    public function __invoke()
    {
        // ニュースカテゴリー
        $addNewsCategoryData = [
            [
                'news_category' => 1,
                'category_name'=>'HELP', // お助け情報
            ],
            [
                'news_category' => 2,
                'category_name'=>'GACHA', //ガチャ
            ],
            [
                'news_category' => 3,
                'category_name'=>'EVENT', // 開催情報
            ],
            [
                'news_category' => 4,
                'category_name'=>'DEFECT', // 不具合
            ],
        ];

        // ミッションマスター
        $addnewsData = [
            [
                'news_id'=>1010001,
                'news_category'=>1,
                'news_name'=>'お助けテスト',
                'news_content'=>'テスト投稿',
                'display_priority'=>1,
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            [
                'news_id'=>1020001,
                'news_category'=>2,
                'news_name'=>'ガチャテスト',
                'news_content'=>'テスト投稿',
                'display_priority'=>1,
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            [
                'news_id'=>1030001,
                'news_category'=>3,
                'news_name'=>'開催テスト',
                'news_content'=>'テスト投稿',
                'display_priority'=>1,
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
            [
                'news_id'=>1040001,
                'news_category'=>4,
                'news_name'=>'不具合テスト',
                'news_content'=>'テスト投稿',
                'display_priority'=>1,
                'period_start'=>'2024-03-10 00:00:00',
                'period_end'=>'2038-12-31 23:59:59',
            ],
        ];

        DB::transaction(function() use ($addNewsCategoryData,$addnewsData){
            // ミッションカテゴリー
            foreach($addNewsCategoryData as $data)
            {
                $check = NewsCategory::where('news_category',$data['news_category'])->first();
                if($check == null)
                {
                    NewsCategory::create([
                        'news_category'=>$data['news_category'],
                        'category_name'=>$data['category_name'],
                    ]);
                }
            }
            // ミッションマスター
            foreach($addnewsData as $data)
            {
                $check = News::where('news_id',$data['news_id'])->first();
                if($check == null)
                {
                    News::create([
                        'news_id'=>$data['news_id'],
                        'news_category'=>$data['news_category'],
                        'news_name'=>$data['news_name'],
                        'news_content'=>$data['news_content'],
                        'display_priority'=>$data['display_priority'],
                        'period_start'=>$data['period_start'],
                        'period_end'=>$data['period_end'],
                    ]);
                }
            }
        });
    }   
}

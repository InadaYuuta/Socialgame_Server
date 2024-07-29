<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;

use Illuminate\Support\Facades\DB;

class AddNewsController extends Controller
{
    /* お知らせの追加
    * nId = お知らせID 作り方は10XYYYY Xにはカテゴリが　Yには番号が入る、重複はしないから前の番号を見て作る、TODO　ただこれだとミスが出そうだからミッションIDを自動発行してくれる機能を作るのもいいかもしれない
    * category = お知らせカテゴリー
    * name = お知らせの見出し
    * content = お知らせの内容
    * priority = 表示優先度、高いほど上部に表示される
    * start = 掲載開始日時
    * end = 掲載終了日時
    */
    // TODO: 今後サイトを作ることになったら、ボタンとかで簡単にニュース追加できるように改善したい
    public function __invoke(Request $request)
    {
        $addNewsData = [
            'news_id'=>$request->nId,
            'news_category'=>$request->category,
            'news_name'=>$request->name,
            'news_content'=>$request->content,
            'display_priority'=>$request->priority,
            'period_start'=>$request->start,
            'period_end'=>$request->end,
        ];

        DB::transaction(function() use ($addNewsData){
                $check = News::where('news_id',$addNewsData['news_id'])->first();
                // ニュース作成
                if($check == null)
                {
                    News::create([
                        'news_id'=>$addNewsData['news_id'],
                        'news_category'=>$addNewsData['news_category'],
                        'news_name'=>$addNewsData['news_name'],
                        'news_content'=>$addNewsData['news_content'],
                        'display_priority'=>$addNewsData['display_priority'],
                        'period_start'=>$addNewsData['period_start'],
                        'period_end'=>$addNewsData['period_end'],
                    ]);
                }
        });
    }   
}

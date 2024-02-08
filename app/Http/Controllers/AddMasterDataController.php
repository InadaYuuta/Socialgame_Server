<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemsCategorie;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AddMasterDataController extends Controller
{
    public function __invoke()
    {
        // 追加データ宣言
        $addDataFirst = new ItemsCategorie;
        $addDataFirst->item_category = 1;
        $addDataFirst->category_name = 'スタミナ回復アイテム';

        $addDataSecond = new ItemsCategorie;
        $addDataSecond->item_category = 2;
        $addDataSecond->category_name = '強化ポイント';

        $addDataThird = new ItemsCategorie;
        $addDataThird->item_category = 3;
        $addDataThird->category_name = '交換アイテム';

        $addDataFourth = new ItemsCategorie;
        $addDataFourth->item_category = 4;
        $addDataFourth->category_name = '凸アイテム';


        DB::transaction(function() use($addDataFirst,$addDataSecond,$addDataThird,$addDataFourth){
            $firstData = ItemsCategorie::create([
            'item_category'=>$addDataFirst->item_category,
            'category_name'=>$addDataFirst->category_name,
            ]);
            $secondData = ItemsCategorie::create([
            'item_category'=>$addDataSecond->item_category,
            'category_name'=>$addDataSecond->category_name,
            ]);
            $thirdData = ItemsCategorie::create([
            'item_category'=>$addDataThird->item_category,
            'category_name'=>$addDataThird->category_name,
            ]);
            $fourthData = ItemsCategorie::create([
            'item_category'=>$addDataFourth->item_category,
            'category_name'=>$addDataFourth->category_name,
            ]);
        });
    }
}

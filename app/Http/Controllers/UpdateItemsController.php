<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use App\Models\ItemInstance;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UpdateItemsController extends Controller
{
    public function __invoke(Request $request)
    {
        $result = 0;
        // ユーザー情報取得
        $userData = Users::where('user_id',$request->uid)->first();
        
        // アイテムテーブルのデータを更新する
        DB::transaction(function() use($userData,$result,$request){
            $result = ItemInstance::where('manage_id',$userData->manage_id)->where('item_id',$request->iid)->update([
                'item_num' => $request->inum,
            ]);
        });

        $response = [
            'items' => ItemInstance::where('manage_id',$userData['manage_id'])->where('item_id',$request->iid)->get(),
        ];
        return json_encode($response);
    }
}

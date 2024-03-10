<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use App\Models\ItemInstance;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ItemRegistrationController extends Controller
{
    public function __invoke(Request $request)
    {
        // ユーザー情報取得
        $userData = Users::where('user_id',$request->uid)->first();
        
        // アイテムテーブルにデータを登録する
        DB::transaction(function() use($userData){
            $item_data_list = Item::all();
            foreach($item_data_list as $item_data)
            {
                $checkManageId = ItemInstance::where('manage_id',$userData['manage_id'])->where('item_id',$item_data['item_id'])->first();
                if($checkManageId == null)
                {
                    $itemsData = ItemInstance::create([
                    'manage_id'=>$userData->manage_id,
                    'item_id'=>$item_data['item_id'],
                    'item_num'=>config('constants.ITEM_NUM'),
                    'used_num'=>config('constants.USED_NUM'),
                    ]);

                }
            }
        });

        $response = [
            'items' => ItemInstance::where('manage_id',$userData['manage_id'])->get(),
        ];
        return json_encode($response);
    }
}

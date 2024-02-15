<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use App\Models\UserWallet;
use App\Models\WeaponInstance;
use App\Models\ItemInstance;
use App\Models\GachaWeapon;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class GachaExecuteController extends Controller
{
    public function __invoke(Request $request)
    {
        $result = 0;
        // ユーザー情報取得
        $userData = Users::where('user_id',$request->uid)->first();
        // 何回ガチャを回すか
        $gacha_count=$request->gCount;

        // ウォレット情報取得
        $walletData = UserWallet::where('manage_id',$userData->manage_id)->first();

        // ガチャ武器データ取得
        // TODO: 取り急ぎガチャひとつでやるからあとからちゃんとガチャIDとる処理を追記
        $gachaWeaponData = GachaWeapon::where('gacha_id',100001)->get();
        // 重さのデータ
        $weightData = [];
        DB::transaction(function() use($gachaWeaponData,&$weightData){
            foreach($gachaWeaponData as $data)
            {
                $weightData[] =[
                        'weapon_id'=>$data->weapon_id,
                        'weight'=>$data->weight,
                    ];
                }
            });
            
        // 抽選用データ
        $gottenWeaponIds = [];
        // ガチャを回す
        DB::transaction(function() use($gacha_count,$weightData,&$gottenWeaponIds){
            for($i = 0; $i < $gacha_count; $i++)
            {
                // 抽選処理----
                $gacha_result = false;
                
                // 重さの合計
                $total_weight = 100000;

                // ランダムな数字
                $target = mt_rand(0,$total_weight);

                $weight = 0;
                // 取得した値と１ずつ比較
                foreach($weightData as $data)
                {
                    $weight = $data['weight'];
                    if($weight >= $target)
                    {
                        // 当選した武器のIDを保存して終了
                        $gacha_result = (int)$data['weapon_id'];
                        break;
                    }
                    $target -= $weight;
                }
                // ----

                // 排出データの追加
                $gottenWeaponIds[] = [
                    'weapon_id' => $gacha_result,
                ];
            }
        });

        // 新規の確認
        $getFragmentItem = 0;
        $gacha_result = [];
        $newWeaponIds = [];
        DB::transaction(function() use(&$newWeaponIds,&$getFragmentItem,&$gacha_result,$gottenWeaponIds,$userData){
            foreach($gottenWeaponIds as $data)
            {
                // 所持済みかどうか確認
                $hasCheck = WeaponInstance::where('manage_id',$userData->manage_id)->where('weapon_id',$data['weapon_id'])->first();
                if($hasCheck == null)
                {
                    // 未所持なら所持に
                    WeaponInstance::create([
                        'manage_id'=>$userData->manage_id,
                        'weapon_id'=>$data['weapon_id'],
                    ]);
                    $newWeaponIds[] = [
                        'weapon_id'=>$data['weapon_id'],
                    ];
                }
                else
                {
                    // 所持済みのためポイントに変換 TODO:個々もちゃんとレアリティとってやるように直す
                   switch($data['weapon_id'])
                   {
                    case 1010001:
                    case 1020001:
                    case 1030001:
                        $getFragmentItem += 1;
                        break;
                    case 2020001:
                        $getFragmentItem += 3;
                        break;
                        case 3010001:
                        $getFragmentItem += 20;
                        break;
                   }
                }
                $gacha_result = [
                    $data['weapon_id']
                ]
                ;
            }
        });

        // ウォレットとアイテムの更新
        DB::transaction(function() use($userData,$gacha_count,$walletData,$getFragmentItem){
            $constantAmount = $gacha_count * 3; // 消費する通貨量
            $freeCurrency = $walletData->free_amount;
            $paidCurrency = $walletData->paid_amount;
            $resultCurrency = 0;
            // 無課金分だけでガチャできる場合
            if($freeCurrency >= $constantAmount)
            {
                $freeCurrency -= $constantAmount;
            }
            // 無課金+有償でガチャが引ける場合
            else if($paidCurrency >= $constantAmount)
            {
                $resultCurrency = $constantAmount - $freeCurrency;
                $paidCurrency -= $resultCurrency;
            }
            if($paidCurrency ==null){$paidCurrency = 0;}
            $result=UserWallet::where('manage_id',$userData->manage_id)->update([
                'free_amount' => $resultCurrency,
                'paid_amount' => $paidCurrency,
            ]);


            $result_item_num = ItemInstance::where('manage_id',$userData->manage_id)->where('item_id',30001)->first()->item_num;
            // アイテムの更新
            $result=ItemInstance::where('manage_id',$userData->manage_id)->where('item_id',30001)->update([
                'item_num' => $result_item_num + $getFragmentItem,
            ]);
        });

        $response = [
            'wallets' => UserWallet::where('manage_id',$userData->manage_id)->first(),
            'items'=>ItemInstance::where('manage_id',$userData->manage_id)->get(),
            'weapons'=>WeaponInstance::where('manage_id',$userData->manage_id)->get(),
            'gacha_result' => $gacha_result,
            'new_weapons'=>$newWeaponIds,
            'fragment_num'=>$getFragmentItem,
        ];

        return json_encode($response);
    }
}

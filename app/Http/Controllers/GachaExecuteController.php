<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\GameUtilService;

use App\Models\User;
use App\Models\UserWallet;
use App\Models\WeaponInstance;
use App\Models\ItemInstance;
use App\Models\GachaWeapon;
use App\Models\GachaLog;
use App\Models\Weapon;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GachaExecuteController extends Controller
{
    /* ガチャの結果、ガチャを回す処理 
    /* uid = ユーザーID
    /* gCount = ガチャを回す回数
    */
    public function __invoke(Request $request)
    {
        $result = 0;
        $errcode = '';
        $response = [];

        // --- Auth処理(ログイン確認)-----------------------------------------
        // ユーザーがログインしていなかったらリダイレクト
        if (!Auth::hasUser()) {
            $response = [
                'errcode' => config('constants.ERRCODE_LOGIN_USER_NOT_FOUND'),
            ];
            return json_encode($response);
        }

        $authUserData = Auth::user();

         // ユーザー情報取得
         $userData = User::where('user_id',$request->uid)->first();
       
        // ユーザー管理ID
        $manage_id = $userData->manage_id;

        // ログインしているユーザーが自分と違ったらリダイレクト
        if ($manage_id != $authUserData->manage_id) {
            $response = [
                'errcode' => config('constants.ERRCODE_LOGIN_SESSION'),
            ];
            return json_encode($response);
        }
        // -----------------------------------------------------------------
        // 何回ガチャを回すか
        $gacha_count=$request->gCount;

        // ウォレット情報
        $walletData = UserWallet::where('manage_id',$manage_id)->first();

        // 抽選用データ
        $gottenWeaponIds = [];

        // ガチャ武器データ取得
        // TODO: 複数のガチャを実装する場合は処理を変更
        $gachaWeaponData = GachaWeapon::where('gacha_id',100001)->get();
        // 重さのデータ
        $weightData = [];

        // 新規の確認
        $getExchangeItem = 0;
        $gacha_result = '';
        $newWeaponIds = [];

        // 消費する通貨量
        $consumptionAmount = $gacha_count * 30; 
        $freeCurrency = $walletData->free_amount;
        $paidCurrency = $walletData->paid_amount;

        // エラーチェック
        $check = $freeCurrency + $paidCurrency;
        if($check < $consumptionAmount)
        {
            $errcode = config('constants.ERRCODE_NOT_ENOUGH_CURRENCY');
                $response = [
                    'errcode' => $errcode,
                ];
                return json_encode($response);
        } // 所持通貨が足りない

        // セクション開始
        DB::transaction(function() use (&$result,$manage_id,$gachaWeaponData,&$weightData,$gacha_count,&$gottenWeaponIds,&$newWeaponIds,&$getExchangeItem,&$gacha_result,$userData,$walletData,$consumptionAmount,$freeCurrency,$paidCurrency){

            // ログ関連
            $log_category = 0;
            $log_context = '';

            foreach($gachaWeaponData as $data)
            {
                $weightData[] =[
                        'weapon_id' => $data->weapon_id,
                        'weight' => $data->weight,
                    ];
                }
       
            // ガチャを回す
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
        
            foreach($gottenWeaponIds as $data)
            {
                // 所持済みかどうか確認
                $hasCheck = WeaponInstance::where('manage_id',$manage_id)->where('weapon_id',$data['weapon_id'])->first();
                if($hasCheck == null)
                {
                    $rarity = Weapon::where('weapon_id',$data['weapon_id'])->first();
                    // 未所持なら所持に
                    WeaponInstance::create([
                        'manage_id' => $manage_id,
                        'weapon_id' => $data['weapon_id'],
                        'rarity_id' => $rarity->rarity_id,
                    ]);
                    $newWeaponIds[] = [
                        'weapon_id' => $data['weapon_id'],
                    ];

                    // ログを追加する処理(武器更新)
                    $weaponData = WeaponInstance::where('manage_id',$manage_id)->get();
                    $log_category = config('constants.WEAPON_DATA');
                    $log_context = config('constants.GET_WEAPON').$weaponData;
                    GameUtilService::logCreate($manage_id,$log_category,$log_context);

                }
                else
                {
                    $item_id = 0; // アイテムのID
                    $getConvexItem = 0;  // 凸アイテムを取得したかどうか
                    $getExchangeNum = 0; //何個交換アイテムを手に入れたか

                    // TODO: もっと武器の種類が増えたときに大変だからメソッド化する
                    switch($data['weapon_id'])
                    {
                        case config('constants.NORMAL_SWORD_ID'): // 普通の剣
                           $item_id = config('constants.NORMAL_SWORD_ITEM_ID');
                           $getExchangeNum = 1;
                           $getConvexItem = 1;
                            break;
                        case config('constants.NORMAL_BOW_ID'): // 普通の弓
                            $item_id = config('constants.NORMAL_BOW_ITEM_ID');
                           $getExchangeNum = 1;
                           $getConvexItem = 1;
                            break;
                        case config('constants.NORMAL_SPEAR_ID'): // 普通の槍
                            $item_id = config('constants.NORMAL_SPEAR_ITEM_ID');
                           $getExchangeNum = 1;
                           $getConvexItem = 1;
                            break;
                        case config('constants.STRONG_BOW_ID'): // 強い弓
                            $item_id = config('constants.STRONG_BOW_ITEM_ID');
                            $getExchangeNum = 10;
                            $getConvexItem = 1;
                            break;
                        case config('constants.VERY_STRONG_SWORD_ID'): // めっちゃ強い剣
                            $item_id = config('constants.VERY_STRONG_SWORD_ITEM_ID');
                           $getExchangeNum = 30;
                           $getConvexItem = 1;
                            break;
                    }

                    // 凸アイテムの所持数確認と増加処理、上限までもってたら交換アイテムを増加
                    if($getConvexItem > 0)
                    {
                        $itemBase = ItemInstance::where('manage_id',$manage_id)->where('item_id',$item_id);
                        $itemData = $itemBase->first();
                        $itemNum = $itemData->item_num; // 所持数
                        $usedNum = $itemData->used_num; // 使用数
                        // ゲーム全体を通して5個まで入手
                        if($itemNum + $usedNum < 5)     
                        {
                            $addItemData = $itemBase->update([
                                'item_num' => $itemNum + 1,
                            ]);

                            // ログを追加する処理(アイテム更新)
                            $convexItemData = ItemInstance::where('manage_id',$manage_id)->where('item_id',$item_id)->get();
                            $log_category = config('constants.ITEM_DATA');
                            $log_context = config('constants.GET_ITEM').$getConvexItem.'/'.$convexItemData;
                            GameUtilService::logCreate($manage_id,$log_category,$log_context);

                       }
                       else
                       {
                            $getExchangeItem += $getExchangeNum;
                       }
                    }

                    // 桁でレアリティを分別
                    $currentDigitNum = 1;
                    $digit = 7;
                    $num = abs($data['weapon_id']);
                    
                    while($num > 0){
                        if($currentDigitNum === $digit){
                            $num %= 10;
                            break;
                        }
                        $num = (int)floor($num / 10);
                        $currentDigitNum++;
                    }

                    // 所持済みのため交換アイテムに変換
                   switch($num)
                   {
                    case 1:
                        $getExchangeItem += 1;
                        break;
                    case 2:
                        $getExchangeItem += 5;
                        break;
                    case 3:
                        $getExchangeItem += 10;
                        break;
                   }
                }
                $gacha_result = $gacha_result . "/" .$data['weapon_id'];

                // 獲得履歴を追加
               $gacha_log = GachaLog::create([
                    'manage_id'=>$manage_id,
                    'gacha_id'=>100001, // TODO: ガチャが増えるようならそれ用に書き換える、今回はひとつなのでこのまま
                    'weapon_id'=>$data['weapon_id'],
                ]);
            }

            // ウォレットとアイテムの更新
            $resultCurrency = 0;

            // 無課金分だけでガチャできる場合
            if($freeCurrency >= $consumptionAmount)
            {
                $freeCurrency -= $consumptionAmount;
            }
            // 無課金+有償でガチャが引ける場合
            else if($paidCurrency >= $consumptionAmount)
            {
                $resultCurrency = $consumptionAmount - $freeCurrency;
                $paidCurrency -= $resultCurrency;
            }
            if($paidCurrency == null){$paidCurrency = 0;}
            $result=UserWallet::where('manage_id',$manage_id)->update([
                'free_amount' => $resultCurrency,
                'paid_amount' => $paidCurrency,
            ]);

            // ログを追加する処理(ウォレット更新)
            $walletData = UserWallet::where('manage_id',$manage_id)->first();
            $log_category = config('constants.CURRENCY_DATA');
            $log_context = config('constants.USE_CURRENCY').$consumptionAmount.'/'.$walletData;
            GameUtilService::logCreate($manage_id,$log_category,$log_context);

            $result_item_num = ItemInstance::where('manage_id',$manage_id)->where('item_id',30001)->first()->item_num;
            // アイテムの更新
            $result=ItemInstance::where('manage_id',$manage_id)->where('item_id',30001)->update([
                'item_num' => $result_item_num + $getExchangeItem,
            ]);

            // ログを追加する処理(アイテム更新)
            $item_id = 30001; // 交換アイテム
            $exchangeItemData = ItemInstance::where('manage_id',$manage_id)->where('item_id',$item_id)->get();
            $log_category = config('constants.ITEM_DATA');
            $log_context = config('constants.GET_ITEM').$getExchangeItem.'/'.$exchangeItemData;
            GameUtilService::logCreate($manage_id,$log_category,$log_context);

            $result = 1;
        });

        switch($result)
        {
            case 0:
                $errcode = config('constants.ERRCODE_CANT_GACHA');
                $response = [
                    'errcode' => $errcode,
                ];
                break;
            case 1:
                $response = [
                    'wallets' => UserWallet::where('manage_id',$manage_id)->first(),
                    'items'=> ItemInstance::where('manage_id',$manage_id)->get(),
                    'weapons'=> WeaponInstance::where('manage_id',$manage_id)->get(),
                    'gacha_result' =>$gacha_result,
                    'new_weapons'=>$newWeaponIds,
                    'fragment_num'=>$getExchangeItem,
                ];
                break;
        }

        return json_encode($response);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentShop;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AddPaymentShopTableDataController extends Controller
{
    public function __invoke()
    {
        // 追加データ宣言
        $addData = [
            [
                'product_id' => 10001,
                'product_name' => '通貨10個',
                'price' => 120,
                'paid_currency' => 10,
                'bonus_currency' => 0,
            ],
            [
                'product_id' => 10002,
                'product_name' => '通貨50個',
                'price' => 480,
                'paid_currency' => 40,
                'bonus_currency' => 10,
            ],
            [
                'product_id' => 10003,
                'product_name' => '通貨210個',
                'price' => 1600,
                'paid_currency' => 130,
                'bonus_currency' => 80,
            ],
            [
                'product_id' => 10004,
                'product_name' => '通貨410個',
                'price' => 3000,
                'paid_currency' => 250,
                'bonus_currency' => 160,
            ],
            [
                'product_id' => 10005,
                'product_name' => '通貨770個',
                'price' => 4900,
                'paid_currency' => 420,
                'bonus_currency' => 350,
            ],
            [
                'product_id' => 10006,
                'product_name' => '通貨1680個',
                'price' => 10000,
                'paid_currency' => 860,
                'bonus_currency' => 820,
            ],
        ];

        // 登録
        DB::transaction(function () use ($addData) {
            foreach ($addData as $data) {
                PaymentShop::create([
                    'product_id' => $data['product_id'],
                    'product_name' => $data['product_name'],
                    'price' => $data['price'],
                    'paid_currency' => $data['paid_currency'],
                    'bonus_currency' => $data['bonus_currency'],
                ]);
            }
        });
    }
}

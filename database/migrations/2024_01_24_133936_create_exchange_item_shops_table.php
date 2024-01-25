<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exchange_item_shops', function (Blueprint $table) {
            $table->unsignedBigInteger('exchange_product_id')->default(0)->comment('交換アイテムの商品ID');
            $table->unsignedTinyInteger('exchange_item_category')->default(0)->comment('交換アイテムのカテゴリー');
            $table->string('exchange_item_name')->default('no name')->comment('交換アイテムの名前(商品名)');
            $table->unsignedSmallInteger('exchange_item_amount')->default(0)->comment('交換でもらえるアイテムの量');
            $table->unsignedSmallInteger('exchange_price')->default(0)->comment('交換に必要アイテム数');
            $table->dateTime('created')->useCurrent()->comment('作成日時');
            $table->dateTime('modified')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
            $table->primary('exchange_product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exchange_item_shops');
    }
};

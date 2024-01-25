<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exchange_item_categories', function (Blueprint $table) {
            $table->unsignedTinyInteger('exchange_item_category')->default(0)->comment('交換アイテムのカテゴリー');
            $table->string('category_name')->default('no name')->comment('カテゴリーの名前');
            $table->dateTime('created')->useCurrent()->comment('作成日時');
            $table->dateTime('modified')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
            $table->primary('exchange_item_category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exchange_item_categories');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('item_id')->comment('アイテムID');
            $table->string('item_name')->default('no name')->comment('アイテム名');
            $table->unsignedSmallInteger('item_category')->default(0)->index()->comment('アイテムカテゴリー');
            $table->dateTime('created')->useCurrent()->comment('作成日時');
            $table->dateTime('modified')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};

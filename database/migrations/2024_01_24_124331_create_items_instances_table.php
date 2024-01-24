<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items_instances', function (Blueprint $table) {
            $table->unsignedBigInteger('manage_id')->default(0)->comment('ユーザー管理ID');
            $table->unsignedBigInteger('item_id')->default(0)->comment('アイテムID');
            $table->unsignedMediumInteger('has_stamina_item_num')->default(0)->comment('スタミナアイテムの所持数');
            $table->unsignedMediumInteger('has_exchange_item_num')->default(0)->comment('交換アイテムの所持数');
            $table->dateTime('created')->useCurrent()->comment('作成日時');
            $table->dateTime('modified')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
            $table->primary('manage_id');
            $table->unique(['item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items_instances');
    }
};

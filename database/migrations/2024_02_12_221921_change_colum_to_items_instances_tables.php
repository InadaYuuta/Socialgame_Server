<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items_instances', function (Blueprint $table) {
            $table->unsignedMediumInteger('item_num')->default(0)->comment('アイテムの所持数')->change();
            $table->unsignedMediumInteger('used_num')->default(0)->comment('アイテムの使用回数')->change();
        });
    }

    public function down(): void
    {
        Schema::table('items_instances', function (Blueprint $table) {
            $table->unsignedMediumInteger('item_num')->default(0)->comment('スタミナアイテムの所持数')->change();
            $table->unsignedMediumInteger('used_num')->default(0)->comment('交換アイテムの所持数')->change();
        });
    }
};

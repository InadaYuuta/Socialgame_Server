<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->unsignedTinyInteger('item_category')->default(0)->comment('アイテムのカテゴリー')->change();
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->unsignedSmallInteger('item_category')->default(0)->comment('アイテムのカテゴリー')->change();
        });
    }
};

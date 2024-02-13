<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items_instances', function (Blueprint $table) {
            $table->renameColumn('has_stamina_item_num','item_num');
            $table->renameColumn('has_exchange_item_num','used_num');
        });
    }

    public function down(): void
    {
        Schema::table('items_instances', function (Blueprint $table) {
            $table->renameColumn('item_num','has_stamina_item_num');
            $table->renameColumn('used_num','has_exchange_item_num');
        });
    }
};
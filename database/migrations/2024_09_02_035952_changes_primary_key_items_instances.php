<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items_instances', function (Blueprint $table) {
            $table->dropIndex('items_instances_item_id_unique');
        });
    }

    public function down(): void
    {
        Schema::table('items_instances', function (Blueprint $table) {
        });
    }
};

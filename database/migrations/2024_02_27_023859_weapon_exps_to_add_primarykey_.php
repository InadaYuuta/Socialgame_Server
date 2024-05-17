<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('weapon_exps', function (Blueprint $table) {
            $table->primary(['rarity_id','level']);
        });
    }

    public function down(): void
    {
        Schema::table('weapon_exps', function (Blueprint $table) {
            $table->dropPrimary(['rarity_id','level']);
        });
    }
};

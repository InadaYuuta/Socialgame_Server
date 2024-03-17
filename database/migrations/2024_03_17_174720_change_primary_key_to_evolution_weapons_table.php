<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evolution_weapons', function (Blueprint $table) {
            $table->primary('evolution_weapon_id');
        });
    }

    public function down(): void
    {
        Schema::table('evolution_weapons', function (Blueprint $table) {
            $table->dropPrimary('evolution_weapon_id');
        });
    }
};

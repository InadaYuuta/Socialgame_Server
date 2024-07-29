<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gacha_weapons', function (Blueprint $table) {
            $table->dropPrimary('weapon_id');
            $table->primary(['gacha_id','weapon_id']);
        });
    }

    public function down(): void
    {
        Schema::table('gacha_weapons', function (Blueprint $table) {
            $table->dropPrimary(['gacha_id','weapon_id']);
        });
    }
};

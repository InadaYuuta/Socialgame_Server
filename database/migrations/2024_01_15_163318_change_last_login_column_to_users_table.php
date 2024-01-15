<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dateTime('stamina_updated')->useCurrentOnUpdate()->change();
            $table->dateTime('last_login')->useCurrentOnUpdate()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dateTime('stamina_updated')->useCurrentOnUpdate(false)->change();
            $table->dateTime('last_login')->useCurrentOnUpdate(false)->change();
        });
    }
};

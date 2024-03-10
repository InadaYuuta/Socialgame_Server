<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gacha_logs', function (Blueprint $table) {
            $table->bigIncrements('gacha_log_id')->change();
        });
    }
    
    public function down(): void
    {
        Schema::table('gacha_logs', function (Blueprint $table) {
            $table->dropPrimary(['gacha_log_id']);
            //$table->bigIncrements('gacha_log_id')->change();
        });
    }
};

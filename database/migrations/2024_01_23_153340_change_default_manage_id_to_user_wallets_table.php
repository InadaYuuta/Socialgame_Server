<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_wallets', function (Blueprint $table) {
            $table->unsignedBigInteger('manage_id')->default(0)->change();
        });
    }
    
    public function down(): void
    {
        Schema::table('user_wallets', function (Blueprint $table) {
            $table->unsignedBigInteger('manage_id')->default(null)->change();
        });
    }
};

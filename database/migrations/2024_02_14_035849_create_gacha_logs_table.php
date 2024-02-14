<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gacha_logs', function (Blueprint $table) {
            $table->bigIncrements('gacha_log_id')->comment('ガチャを引いたログのID');
            $table->unsignedBigInteger('manage_id')->index()->default(0)->comment('ユーザー管理ID');
            $table->unsignedBigInteger('gacha_id')->default(0)->comment('ガチャID');
            $table->unsignedBigInteger('weapon_id')->default(0)->comment('武器ID');
            $table->dateTime('created')->useCurrent()->comment('作成日時、排出日');
            $table->dateTime('modified')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gacha_logs');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gacha_weapons', function (Blueprint $table) {
            $table->unsignedBigInteger('gacha_id')->default(0)->comment('ガチャID');
            $table->unsignedBigInteger('weapon_id')->default(0)->comment('武器ID');
            $table->unsignedInteger('weight')->default(0)->comment('重さ');
            $table->dateTime('created')->useCurrent()->comment('作成日時');
            $table->dateTime('modified')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
            $table->primary('weapon_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gacha_weapons');
    }
};

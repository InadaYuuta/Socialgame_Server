<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weapon_instances', function (Blueprint $table) {
            $table->unsignedBigInteger('manage_id')->default(0)->comment('ユーザー管理ID');
            $table->unsignedBigInteger('weapon_id')->default(0)->comment('武器ID');
            $table->unsignedBigInteger('rarity_id')->default(0)->comment('レアリティID');
            $table->unsignedTinyInteger('level')->default(1)->comment('レベル');
            $table->unsignedTinyInteger('level_max')->default(50)->comment('レベル上限');
            $table->unsignedInteger('current_exp')->default(0)->comment('現在の経験値');
            $table->unsignedTinyInteger('limit_break')->default(0)->comment('限界突破');
            $table->unsignedTinyInteger('limit_break_max')->default(5)->comment('限界突破上限');
            $table->unsignedTinyInteger('evolution')->default(0)->comment('進化');
            $table->dateTime('created')->useCurrent()->comment('作成日時');
            $table->dateTime('modified')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
            $table->primary(['manage_id','weapon_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weapon_instances');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weapon_exps', function (Blueprint $table) {
            $table->unsignedBigInteger('rarity_id')->default(0)->comment('レアリティID');
            $table->unsignedTinyInteger('level')->default(1)->comment('レベル');
            $table->unsignedSmallInteger('use_reinforce_point')->default(0)->comment('使用する強化ポイント');
            $table->dateTime('created')->useCurrent()->comment('作成日時');
            $table->dateTime('modified')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
            $table->primary('rarity_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weapon_exps');
    }
};

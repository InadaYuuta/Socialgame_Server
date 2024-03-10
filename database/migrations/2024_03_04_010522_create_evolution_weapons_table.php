<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evolution_weapons', function (Blueprint $table) {
            $table->unsignedBigInteger('evolution_weapon_id')->default(0)->comment('進化後武器ID');
            $table->unsignedBigInteger('rarity_id')->default(0)->comment('レアリティID');
            $table->unsignedTinyInteger('weapon_category')->default(0)->comment('武器のカテゴリー');
            $table->string('weapon_name')->default('no name')->charset('utf8')->comment('武器の名前');
            $table->dateTime('created')->useCurrent()->comment('作成日時');
            $table->dateTime('modified')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evolution_weapons');
    }
};

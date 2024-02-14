<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weapon_rarities', function (Blueprint $table) {
            $table->unsignedBigInteger('rarity_id')->default(0)->comment('レアリティID');
            $table->string('rarity_name')->charset('utf8')->default('no name')->comment('レアリティ名');
            $table->dateTime('created')->useCurrent()->comment('作成日時');
            $table->dateTime('modified')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
            $table->primary('rarity_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weapon_rarities');
    }
};

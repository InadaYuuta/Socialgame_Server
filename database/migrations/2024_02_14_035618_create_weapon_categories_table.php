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
        Schema::create('weapon_categories', function (Blueprint $table) {
            $table->unsignedTinyInteger('weapon_category')->default(0)->comment('武器カテゴリー');
            $table->string('category_name')->charset('utf8')->default('no name')->comment('武器カテゴリー名');
            $table->dateTime('created')->useCurrent()->comment('作成日時');
            $table->dateTime('modified')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
            $table->primary('weapon_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weapon_categories');
    }
};

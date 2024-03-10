<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mission_categories', function (Blueprint $table) {
            $table->unsignedTinyInteger('mission_category')->default(0)->comment('ミッションカテゴリー');
            $table->string('category_name')->charset('utf8')->default('no name')->comment('カテゴリーの名前');
            $table->dateTime('created')->useCurrent()->comment('作成日時');
            $table->dateTime('modified')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
            $table->primary('mission_category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mission_categories');
    }
};

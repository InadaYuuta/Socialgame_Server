<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_categories', function (Blueprint $table) {
            $table->unsignedTinyInteger('log_category')->default(0)->comment('ログの種類(カテゴリー)');
            $table->string('category_name')->default('no name')->charset('utf8')->comment('カテゴリーの名前');
            $table->dateTime('created')->useCurrent()->comment('作成日時');
            $table->dateTime('modified')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
            $table->primary('log_category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_categories');
    }
};

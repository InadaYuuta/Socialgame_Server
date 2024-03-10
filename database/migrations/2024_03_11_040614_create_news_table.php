<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->unsignedBigInteger('news_id')->default(0)->comment('お知らせID');
            $table->unsignedTinyInteger('news_category')->default(0)->comment('お知らせカテゴリー');
            $table->text('news_name')->charset('utf8')->comment('お知らせ名');
            $table->text('news_content')->charset('utf8')->comment('お知らせの内容');
            $table->unsignedSmallInteger('display_priority')->default(0)->comment('表示優先度');
            $table->datetime('period_start')->default('2024-03-10 00:00:00')->comment('開始日時');
            $table->datetime('period_end')->default('2038-12-31 23:59:59')->comment('終了日時');
            $table->dateTime('created')->useCurrent()->comment('作成日時');
            $table->dateTime('modified')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
            $table->primary('news_id');
            $table->index('news_category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};

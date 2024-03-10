<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('missions', function (Blueprint $table) {
            $table->unsignedBigInteger('mission_id')->default(0)->comment('ミッションID');
            $table->unsignedBigInteger('next_mission_id')->default(0)->comment('次のミッションID');
            $table->text('mission_name')->charset('utf8')->comment('ミッション名');
            $table->text('mission_content')->charset('utf8')->comment('ミッションの内容');
            $table->unsignedTinyInteger('mission_category')->default(0)->comment('ミッションのカテゴリー');
            $table->unsignedTinyInteger('reward_category')->default(0)->comment('報酬のカテゴリー');
            $table->text('mission_reward')->charset('utf8')->comment('ミッションの報酬');
            $table->text('achievement_condition')->charset('utf8')->comment('達成条件(と数値)');
            $table->datetime('period_start')->default('2024-03-10 00:00:00')->comment('開始日時');
            $table->datetime('period_end')->default('2038-12-31 23:59:59')->comment('終了日時');
            $table->dateTime('created')->useCurrent()->comment('作成日時');
            $table->dateTime('modified')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
            $table->primary('mission_id');
            $table->index('mission_category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('missions');
    }
};

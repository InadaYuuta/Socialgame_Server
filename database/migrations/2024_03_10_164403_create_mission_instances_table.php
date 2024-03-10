<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mission_instances', function (Blueprint $table) {
            $table->unsignedBigInteger('manage_id')->default(0)->comment('ユーザー管理ID');
            $table->unsignedBigInteger('mission_id')->default(0)->comment('ミッションID');
            $table->unsignedTinyInteger('achieved')->default(0)->comment('達成');
            $table->unsignedTinyInteger('receipt')->default(0)->comment('受取');
            $table->unsignedSmallInteger('progress')->default(0)->comment('進捗');
            $table->datetime('term')->default('2038-12-31 23:59:59')->comment('期限');
            $table->datetime('validity_term')->default('2024-03-10 00:00:00')->comment('達成日');
            $table->dateTime('created')->useCurrent()->comment('作成日時');
            $table->dateTime('modified')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
            $table->primary(['manage_id','mission_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mission_instances');
    }
};

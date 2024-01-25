<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->bigIncrements('log_id')->comment('ログID');
            $table->unsignedBigInteger('manage_id')->default(0)->comment('ユーザー管理ID');
            $table->unsignedSmallInteger('log_category')->default(0)->comment('ログの種類(カテゴリー');
            $table->text('log_context')->comment('ログの内容');
            $table->dateTime('created')->useCurrent()->comment('作成日時');
            $table->dateTime('modified')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
            $table->index('manage_id');
            $table->index('log_category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};

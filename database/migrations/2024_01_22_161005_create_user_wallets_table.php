<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_wallets', function (Blueprint $table) {
            $table->unsignedBigInteger('manage_id')->comment('ユーザー管理ID');
            $table->unsignedMediumInteger('free_amount')->default(0)->comment('無償残高');
            $table->unsignedMediumInteger('paid_amount')->default(0)->comment('有償残高');
            $table->unsignedMediumInteger('max_amount')->default(99999)->comment('最大残高');
            $table->dateTime('created')->useCurrent()->comment('作成日時');
            $table->dateTime('modified')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
            $table->primary('manage_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_wallets');
    }
};

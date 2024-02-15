<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gacha_periods', function (Blueprint $table) {
            $table->unsignedBigInteger('gacha_id')->default(0)->comment('ガチャID');
            $table->string('gacha_name')->default('no name')->comment('ガチャ名');
            $table->unsignedTinyInteger('single_cost')->default(1)->comment('単発ガチャ価格');
            $table->unsignedTinyInteger('multi_cost')->default(1)->comment('連ガチャ価格');
            $table->dateTime('period_start')->default('2000-01-01 00:00:00')->comment('開始日時');
            $table->dateTime('period_end')->default('2038-12-31 23:59:59')->comment('終了日時');
            $table->dateTime('created')->useCurrent()->comment('作成日時');
            $table->dateTime('modified')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
            $table->primary('gacha_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gacha_periods');
    }
};

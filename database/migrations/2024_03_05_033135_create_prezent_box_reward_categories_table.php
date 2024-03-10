<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reward_categories', function (Blueprint $table) {
            $table->unsignedTinyInteger('reward_category')->default(0)->comment('報酬カテゴリー');
            $table->string('reward_category_name')->default('no name')->charset('utf8')->comment('報酬カテゴリー名');
            $table->dateTime('created')->useCurrent()->comment('作成日時');
            $table->dateTime('modified')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
            $table->primary('reward_category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reward_categories');
    }
};

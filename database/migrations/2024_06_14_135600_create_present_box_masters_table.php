<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whole_present_boxes', function (Blueprint $table) {
            $table->bigIncrements('whole_present_id')->comment('全体プレゼントID');
            $table->unsignedTinyInteger('reward_category')->default(0)->comment('報酬のカテゴリー');
            $table->text('present_box_reward')->charset('utf8')->comment('報酬の内容');
            $table->string('receive_reason')->default('○○の報酬です')->charset('utf8')->comment('受け取った理由');
            $table->datetime('distribution_start')->default('2024-06-14 00:00:00')->comment('配布開始日時');
            $table->datetime('distribution_end')->default('2038-12-31 23:59:59')->comment('配布終了日時');
            $table->dateTime('created')->useCurrent()->comment('作成日時');
            $table->dateTime('modified')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whole_present_boxes');
    }
};

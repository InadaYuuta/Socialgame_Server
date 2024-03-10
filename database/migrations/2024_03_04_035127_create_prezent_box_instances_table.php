<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('prezent_box_instances');
        Schema::create('prezent_box_instances', function (Blueprint $table) {
            $table->unsignedBigInteger('manage_id')->default(0)->comment('ユーザー管理ID');
            $table->unsignedBigInteger('prezent_id')->default(0)->comment('プレゼントID');
            $table->unsignedTinyInteger('reward_category')->default(0)->comment('報酬のカテゴリー');
            $table->text('prezent_box_reward')->charset('utf8')->comment('報酬の内容');
            $table->string('receive_reson')->default('○○の報酬です')->charset('utf8')->comment('受け取った理由');
            $table->unsignedTinyInteger('receipt')->default(0)->comment('受取');
            $table->datetime('receipt_date')->useCurrent()->comment('受取日');
            $table->datetime('display')->default('2038-12-31 23:59:59')->comment('表示期限');
            $table->dateTime('created')->useCurrent()->comment('作成日時');
            $table->dateTime('modified')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
            $table->primary(['manage_id','prezent_id']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('prezent_box_instances');
    }
};

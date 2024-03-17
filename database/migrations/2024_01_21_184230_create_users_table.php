<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->unsignedBigInteger('manage_id')->autoIncrement()->comment('ユーザー管理ID');
            $table->ulid('user_id',26)->charset('utf8')->comment('ユーザーID');
            $table->string('user_name',16)->charset('utf8')->comment('表示名');
            $table->string('handover_passhash',255)->charset('utf8')->comment('引き継ぎパスワードハッシュ');
            $table->unsignedMediumInteger('has_reinforce_point')->default(0)->comment('所持強化ポイント');
            $table->unsignedSmallInteger('user_rank')->default(1)->comment('ユーザーランク');
            $table->unsignedMediumInteger('user_rank_exp')->default(0)->comment('ユーザーランク用の経験値');
            $table->unsignedInteger('login_days')->default(0)->comment('累計ログイン日数');
            $table->unsignedTinyInteger('max_stamina')->default(200)->comment('最大スタミナ');
            $table->unsignedTinyInteger('last_stamina')->default(200)->comment('最終更新時スタミナ');
            $table->dateTime('stamina_updated')->useCurrent()->comment('スタミナ更新日時');
            $table->dateTime('last_login')->useCurrent()->comment('最終ログイン日時');
            $table->dateTime('created')->useCurrent()->comment('作成日時');
            $table->dateTime('modified')->useCurrent()->useCurrentOnUpdate()->comment('更新日時');
            $table->boolean('deleted')->default(0)->comment('削除');
            $table->unique(['user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

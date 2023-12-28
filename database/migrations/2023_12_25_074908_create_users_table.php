<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->unsignedBigInteger('manage_id')->autoIncrement();            // ユーザー管理ID
            $table->char('user_id',26)->charset('utf8');                         // ユーザーID
            $table->char('user_name',16)->charset('utf8');                       // 表示名
            $table->char('handover_passhash',255)->charset('utf8');              // 引き継ぎパスワードハッシュ
            $table->unsignedMediumInteger('has_weapon_exp_point')->default(0);   // 所持武器経験値
            $table->unsignedSmallInteger('user_rank')->default(1);               // ユーザーランク
            $table->unsignedMediumInteger('user_rank_exp')->default(0);          // ユーザーランク用の経験値
            $table->unsignedInteger('login_days')->default(0);                   // 累計ログイン日数
            $table->unsignedTinyInteger('max_stamina')->default(200);            // 最大スタミナ
            $table->unsignedTinyInteger('last_stamina')->default(200);           // 最終更新時スタミナ
            $table->dateTime('stamina_updated')->useCurrent();                   // スタミナ更新日時
            $table->dateTime('last_login')->useCurrent();                        // 最終ログイン日時
            $table->dateTime('created')->useCurrent();                           // 作成日時
            $table->dateTime('modified')->useCurrent()->useCurrentOnUpdate();    // 更新日時
            $table->unsignedTinyInteger('deleted')->default(0);   // TODO:booleanに変更する
            $table->unique(['user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

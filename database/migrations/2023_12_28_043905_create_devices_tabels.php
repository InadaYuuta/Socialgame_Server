<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->char('device_id',50)->charset('utf8');                       // デバイスID
            $table->char('user_id',26)->charset('utf8');                         // ユーザーID
            $table->dateTime('created')->useCurrent();                           // 作成日時
            $table->dateTime('modified')->useCurrent()->useCurrentOnUpdate();    // 更新日時
            $table->unsignedTinyInteger('deleted')->default(0);   
            $table->unique(['device_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('present_box_instances', function (Blueprint $table) {
            $table->unsignedBigInteger('whole_present_id')->default(0)->comment('全体プレゼントID')->after('present_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('present_box_instances', function (Blueprint $table) {
            $table->dropColumn('whole_present_id');
        });
    }
};

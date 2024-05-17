<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('prezent_box_instances','present_box_instances');
        Schema::table('present_box_instances', function (Blueprint $table) {
            $table->dropPrimary(['manage_id','prezent_id']);
            $table->renameColumn('prezent_id','present_id');
            $table->renameColumn('prezent_box_reward','present_box_reward');
            $table->renameColumn('receive_reson','receive_reason');
            $table->primary(['manage_id','present_id']);
        });
    }

    public function down(): void
    {
        
        Schema::rename('present_box_instances','prezent_box_instances');
        Schema::table('prezent_box_instances', function (Blueprint $table) {
            $table->dropPrimary(['manage_id','prezent_id']);
            $table->renameColumn('present_id','prezent_id');
            $table->renameColumn('present_box_reward','prezent_box_reward');
            $table->renameColumn('receive_reason','receive_reson');
            $table->primary(['manage_id','present_id']);
        });
    }
};

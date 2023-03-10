<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldColorScheduleTimeline extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedule_timeline', function (Blueprint $table) {
            //
            $table->string('color', 120)->nullable();
            $table->string('group_color', 120)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedule_timeline', function (Blueprint $table) {
            //
            $table->dropColumn('group_color');
            $table->dropColumn('color');
        });
    }
}

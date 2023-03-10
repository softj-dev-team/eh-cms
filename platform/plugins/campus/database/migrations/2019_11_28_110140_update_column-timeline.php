<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateColumnTimeline extends Migration
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
            $table->string('lecture_room')->nullable();
            $table->string('professor_name')->nullable();
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
            $table->dropColumn('lecture_room');
            $table->dropColumn('professor_name');
        });
    }
}

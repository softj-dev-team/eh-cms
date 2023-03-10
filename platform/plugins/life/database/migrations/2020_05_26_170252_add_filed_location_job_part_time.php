<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFiledLocationJobPartTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs_part_time', function (Blueprint $table) {
            //

            $table->text('pay')->nullable();
            $table->text('location')->nullable();
            $table->text('period')->nullable();
            $table->text('day')->nullable();
            $table->text('time')->nullable();
            $table->text('resume')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs_part_time', function (Blueprint $table) {
            //
            $table->dropColumn('resume');
            $table->dropColumn('time');
            $table->dropColumn('day');
            $table->dropColumn('period');
            $table->dropColumn('location');
            $table->dropColumn('pay');
        });
    }
}

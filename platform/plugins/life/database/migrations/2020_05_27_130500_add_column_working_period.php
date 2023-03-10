<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnWorkingPeriod extends Migration
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
            $table->text('working_period')->nullable();
            $table->text('applying_period')->nullable();
            $table->text('open_position')->nullable();
            $table->text('exact_location')->nullable();
            $table->text('prefer_requirements')->nullable();

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
            $table->dropColumn('prefer_requirements');
            $table->dropColumn('exact_location');
            $table->dropColumn('open_position');
            $table->dropColumn('applying_period');
            $table->dropColumn('working_period');
        });
    }
}

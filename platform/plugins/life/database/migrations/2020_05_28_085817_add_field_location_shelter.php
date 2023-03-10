<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldLocationShelter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shelter', function (Blueprint $table) {
            //
            $table->text('location')->nullable();
            $table->text('size')->nullable();
            $table->text('lease_period')->nullable();
            $table->text('building_type')->nullable();
            $table->text('possible_moving_date')->nullable();
            $table->text('heating_type')->nullable();
            $table->text('option')->nullable();
            $table->text('real_estate')->nullable();
            $table->text('utility')->nullable();
            $table->integer('right_click')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shelter', function (Blueprint $table) {
            //
            $table->dropColumn('right_click');
            $table->dropColumn('utility');
            $table->dropColumn('real_estate');
            $table->dropColumn('option');
            $table->dropColumn('heating_type');
            $table->dropColumn('possible_moving_date');
            $table->dropColumn('building_type');
            $table->dropColumn('lease_period');
            $table->dropColumn('size');
            $table->dropColumn('location');
        });
    }
}

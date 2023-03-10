<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldReasonLife extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sympathy_open_space', function (Blueprint $table) {
            //
            $table->text('reason')->nullable();
        });
        Schema::table('sympathy_flare_market', function (Blueprint $table) {
            //
            $table->text('reason')->nullable();
        });
        Schema::table('sympathy_jobs_part_time', function (Blueprint $table) {
            //
            $table->text('reason')->nullable();
        });
        Schema::table('sympathy_shelter', function (Blueprint $table) {
            //
            $table->text('reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sympathy_open_space', function (Blueprint $table) {
            //
            $table->dropColumn('reason');
        });
        Schema::table('sympathy_flare_market', function (Blueprint $table) {
            //
            $table->dropColumn('reason');
        });
        Schema::table('sympathy_jobs_part_time', function (Blueprint $table) {
            //
            $table->dropColumn('reason');
        });
        Schema::table('sympathy_shelter', function (Blueprint $table) {
            //
            $table->dropColumn('reason');
        });
    }
}

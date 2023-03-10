<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateColumnEvaluation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evaluation', function (Blueprint $table) {
            //
            $table->string('course_code')->nullable();
            $table->string('lecture_room')->nullable();
            $table->string('compete')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('evaluation', function (Blueprint $table) {
            //
            $table->dropColumn('course_code');
            $table->dropColumn('lecture_room');
            $table->dropColumn('compete');
        });
    }
}

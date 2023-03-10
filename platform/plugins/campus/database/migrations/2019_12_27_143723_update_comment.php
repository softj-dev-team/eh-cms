<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateComment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comments_evaluation', function (Blueprint $table) {
            //
            $table->string('grade')->nullable();
            $table->string('assignment')->nullable();
            $table->string('attendance')->nullable();
            $table->text('textbook')->nullable();
            $table->string('team_project')->nullable();
            $table->string('number_times')->nullable();
            $table->string('type')->nullable();
            $table->text('comments')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comments_evaluation', function (Blueprint $table) {
            //
            $table->dropColumn('grade');
            $table->dropColumn('assignment');
            $table->dropColumn('attendance');
            $table->dropColumn('textbook');
            $table->dropColumn('team_project');
            $table->dropColumn('number_times');
            $table->dropColumn('type');
            $table->string('comments')->nullable()->change();
        });
    }
}

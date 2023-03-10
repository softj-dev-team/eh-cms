<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnCategoriesOpenSpace extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('open_space', function (Blueprint $table) {
            //
            $table->integer('right_click')->default(0);
            $table->integer('active_empathy')->default(1);
            $table->unsignedInteger('categories_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('open_space', function (Blueprint $table) {
            //
            $table->dropColumn('categories_id');
            $table->dropColumn('active_empathy');
            $table->dropColumn('right_click');
        });
    }
}

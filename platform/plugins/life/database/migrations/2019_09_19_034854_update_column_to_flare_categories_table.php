<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateColumnToFlareCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories_market', function (Blueprint $table) {
            //
            Schema::table('categories_market', function (Blueprint $table) {
                //
                $table->dropForeign('categories_market_parent_id_foreign');
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories_market', function (Blueprint $table) {
            //
            $table->foreign('parent_id')->references('id')->on('categories_market');
        });
    }
}

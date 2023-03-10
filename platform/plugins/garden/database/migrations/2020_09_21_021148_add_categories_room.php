<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCategoriesRoom extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('egardens', function (Blueprint $table) {
            //
            $table->unsignedInteger('categories_room_id')->nullable();
            $table->foreign('categories_room_id')->references('id')->on('categories_room')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('egardens', function (Blueprint $table) {
            //
            $table->dropForeign(['categories_room_id']);
            $table->dropColumn('categories_room_id');
        });
    }
}

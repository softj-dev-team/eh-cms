<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeForeignRoom extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories_room', function (Blueprint $table) {
            //
            $table->dropForeign(['room_id']);
            $table->foreign('room_id')->references('id')->on('room')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories_room', function (Blueprint $table) {
            //
            $table->dropForeign(['room_id']);
            $table->foreign('room_id')->references('id')->on('members')->onDelete('cascade');

        });
    }
}

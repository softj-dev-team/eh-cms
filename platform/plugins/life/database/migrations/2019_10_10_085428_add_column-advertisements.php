<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnAdvertisements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('advertisements', function (Blueprint $table) {
            //
            $table->dateTime('start')->nullable();
            $table->text('duration')->nullable();
            $table->text('recruitment')->nullable();
            $table->text('contact')->nullable();
            $table->text('file_upload')->nullable();
            $table->text('link')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('advertisements', function (Blueprint $table) {
            //
            $table->dropColumn('start');
            $table->dropColumn('duration');
            $table->dropColumn('recruitment');
            $table->dropColumn('contact');
            $table->dropColumn('file_upload');
            $table->dropColumn('link');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateColumDetailAds extends Migration
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
            // $table->longText('details')->change();
            DB::statement('ALTER TABLE advertisements MODIFY details  LONGTEXT;');
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
            // $table->text('details')->change();
            DB::statement('ALTER TABLE advertisements MODIFY details TEXT;');
        });
    }
}

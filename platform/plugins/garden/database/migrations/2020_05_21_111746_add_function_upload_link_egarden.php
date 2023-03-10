<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFunctionUploadLinkEgarden extends Migration
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
        Schema::table('egardens', function (Blueprint $table) {
            //
            $table->dropColumn('file_upload');
            $table->dropColumn('link');
        });
    }
}

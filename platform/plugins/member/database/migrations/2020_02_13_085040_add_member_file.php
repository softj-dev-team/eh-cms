<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMemberFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_file', function (Blueprint $table) {
            $table->string('f_idx')->nullable();
            $table->string('mem_idx')->nullable();
            $table->string('folder_idx')->nullable();
            $table->string('f_name')->nullable();
            $table->string('f_update')->nullable();
            $table->string('f_upfilename')->nullable();
            $table->string('f_upfileext')->nullable();
            $table->string('f_date')->nullable();
            $table->string('f_size')->nullable();
            $table->string('f_open')->nullable();
            $table->string('f_count')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_file');
    }
}

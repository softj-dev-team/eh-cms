<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMemberFolder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_folder', function (Blueprint $table) {
            $table->string('folder_idx')->nullable();
            $table->string('mem_idx')->nullable();
            $table->string('folder_name')->nullable();
            $table->string('folder_date')->nullable();
            $table->string('folder_open')->nullable();
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
        Schema::dropIfExists('member_folder');
    }
}

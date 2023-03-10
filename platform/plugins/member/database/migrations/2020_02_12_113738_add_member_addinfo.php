<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMemberAddinfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_addinfo', function (Blueprint $table) {
            $table->string('mem_idx');
            $table->string('mem_email')->nullable();
            $table->string('mem_addr')->nullable();
            $table->string('mem_post')->nullable();
            $table->string('mem_phone')->nullable();
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
        Schema::dropIfExists('member_addinfo');
    }
}



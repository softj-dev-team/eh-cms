<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMemberCurrentIp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_current_ip', function (Blueprint $table) {
            $table->string('mem_idx')->nullable();
            $table->string('mem_ip')->nullable();
            $table->string('mem_date')->nullable();
            $table->string('random_id')->nullable();
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
        Schema::dropIfExists('member_current_ip');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMemberFreshToEwhainLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_fresh_to_ewhain_log', function (Blueprint $table) {
            $table->string('mem_idx')->nullable();
            $table->string('fresh_num')->nullable();
            $table->string('mem_num')->nullable();
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
        Schema::dropIfExists('member_fresh_to_ewhain_log');
    }
}

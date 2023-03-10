<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMemberCareer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_career', function (Blueprint $table) {
            $table->string('mem_idx')->nullable();
            $table->string('login_count')->nullable();
            $table->string('good_s_count')->nullable();
            $table->string('gubak_s_count')->nullable();
            $table->string('good_r_count')->nullable();
            $table->string('gubak_r_count')->nullable();
            $table->string('board_count')->nullable();
            $table->string('reple_count')->nullable();
            $table->string('biwon_board_count')->nullable();
            $table->string('biwon_reple_count')->nullable();
            $table->string('hooli_s_count')->nullable();
            $table->string('hooli_r_count')->nullable();
            $table->string('last_login')->nullable();
            $table->string('first_date')->nullable();
            $table->string('rgood_r_count')->nullable();
            $table->string('rgood_s_count')->nullable();
            $table->string('rgubak_r_count')->nullable();
            $table->string('rgubak_s_count')->nullable();
            $table->string('bgood_r_count')->nullable();
            $table->string('bgood_s_count')->nullable();
            $table->string('bgubak_r_count')->nullable();
            $table->string('bgubak_s_count')->nullable();
            $table->string('brgood_r_count')->nullable();
            $table->string('brgood_s_count')->nullable();
            $table->string('brgubak_r_count')->nullable();
            $table->string('brgubak_s_count')->nullable();


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
        Schema::dropIfExists('member_career');
    }
}


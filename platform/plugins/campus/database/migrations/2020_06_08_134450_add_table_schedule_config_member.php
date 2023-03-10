<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableScheduleConfigMember extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_config_member', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('time')->nullable();
            $table->text('day')->nullable();
            $table->text('show_lecture')->nullable();
            $table->unsignedInteger('member_id')->nullable();
            $table->unsignedInteger('schedule_id')->nullable();
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
            $table->foreign('schedule_id')->references('id')->on('schedule')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedule_config_member');
    }
}

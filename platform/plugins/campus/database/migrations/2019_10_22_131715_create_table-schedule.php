<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSchedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('schedule_time')) {
            Schema::create('schedule_time', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('from')->nullable();
                $table->unsignedInteger('to')->nullable();
                $table->unsignedInteger('unit')->nullable();
                $table->string('status', 60)->default('publish');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('schedule_day')) {
            Schema::create('schedule_day', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 120)->nullable();
                $table->string('status', 60)->default('publish');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('schedule')) {
            Schema::create('schedule', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 120)->nullable();
                $table->dateTime('start')->nullable();
                $table->dateTime('end')->nullable();
                $table->unsignedInteger('lookup')->nullable();
                $table->string('id_login')->nullable();//nickname of member
                $table->string('status', 60)->default('publish');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('schedule_timeline')) {
            Schema::create('schedule_timeline', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('schedule_id');
                $table->string('title', 120)->nullable();
                $table->string('day')->nullable();
                $table->float('from')->nullable();
                $table->float('to')->nullable();
                $table->string('status', 60)->default('publish');

                $table->foreign('schedule_id')->references('id')->on('schedule')->onDelete('cascade');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('schedule_share')) {
            Schema::create('schedule_share', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('schedule_id');
                $table->unsignedInteger('member_id');
                $table->string('author'); 

                $table->foreign('schedule_id')->references('id')->on('schedule')->onDelete('cascade');
                $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
                $table->timestamps();
            });
        }
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedule_timeline', function (Blueprint $table) {
            $table->dropForeign('schedule_timeline_schedule_id_foreign');
        });
        Schema::table('schedule_share', function (Blueprint $table) {
            $table->dropForeign('schedule_share_schedule_id_foreign');
            $table->dropForeign('schedule_share_member_id_foreign');
        });
        
        Schema::dropIfExists('schedule_timeline');
        Schema::dropIfExists('schedule_share');
        Schema::dropIfExists('schedule_day');
        Schema::dropIfExists('schedule_time');
        Schema::dropIfExists('schedule');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMasterRoomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('categories_master_rooms')) {
            Schema::create('categories_master_rooms', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('status', 60)->default('publish');
                $table->timestamps();

            });
         }
        if (!Schema::hasTable('master_rooms')) {
            Schema::create('master_rooms', function (Blueprint $table) {
                $table->increments('id');
                $table->string('title')->nullable();
                $table->dateTime('start')->nullable();
                $table->dateTime('end')->nullable();
                $table->integer('enrollment_limit')->default(0);
                $table->text('banner')->nullable();
                $table->text('content')->nullable();
                $table->text('notice')->nullable();
                $table->text('description')->nullable();
                $table->integer('lookup')->default(0);

                $table->unsignedInteger('member_id')->nullable();
                $table->unsignedInteger('categories_master_rooms_id')->default(0);

                $table->string('status', 60)->default('publish');
                $table->timestamps();

                $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
                $table->foreign('categories_master_rooms_id')->references('id')->on('categories_master_rooms')->onDelete('cascade');

            });
        }
        if (!Schema::hasTable('comments_master_rooms')) {
            Schema::create('comments_master_rooms', function (Blueprint $table) {
                //
                $table->bigIncrements('id');
                $table->unsignedInteger('master_rooms_id');
                $table->unsignedInteger('parents_id')->nullable();
                $table->unsignedInteger('member_id')->nullable();
                $table->unsignedInteger('anonymous')->default('0'); //0: true show name, 1: false show 'Anonymous'
                $table->text('content');

                $table->string('status', 60)->default('publish');
                $table->timestamps();

                $table->foreign('master_rooms_id')->references('id')->on('master_rooms')->onDelete('cascade');
                $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
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
        Schema::dropIfExists('comments_master_rooms');
        Schema::dropIfExists('master_rooms');
        Schema::dropIfExists('categories_master_rooms');
    }
}

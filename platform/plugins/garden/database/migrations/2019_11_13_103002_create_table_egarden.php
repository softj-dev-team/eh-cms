<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableEgarden extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('room')) {
            Schema::create('room', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->text('description')->nullable();
                $table->unsignedInteger('member_id')->nullable(); //author
                $table->string('status', 60)->default('publish');
                $table->text('images')->nullable();
                $table->timestamps();

                $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('egardens')) {
            Schema::create('egardens', function (Blueprint $table) {
                $table->increments('id');
                $table->string('title')->nullable();
                $table->text('detail')->nullable();
                $table->integer('lookup')->default(0);
                $table->integer('right_click')->default(0);
                $table->integer('active_empathy')->default(0);
                $table->unsignedInteger('member_id')->nullable(); //Author
                $table->unsignedInteger('room_id')->nullable(); //Room id
                $table->string('status', 60)->default('publish');
                
                $table->timestamps();

                $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
                $table->foreign('room_id')->references('id')->on('room')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('comments_egardens')) {
            Schema::create('comments_egardens', function (Blueprint $table) {
                //
                $table->bigIncrements('id');
                $table->unsignedInteger('egardens_id');
                $table->unsignedInteger('parents_id')->nullable();
                $table->unsignedInteger('member_id')->nullable(); //author
                $table->unsignedInteger('anonymous')->default('0'); //0: true show name, 1: false show 'Anonymous'
                $table->text('content');
                $table->string('status', 60)->default('publish');
                $table->timestamps();

                $table->foreign('egardens_id')->references('id')->on('egardens')->onDelete('cascade');
                $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('room_member')) {
            Schema::create('room_member', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('member_id')->nullable();
                $table->unsignedInteger('room_id')->nullable();
                $table->string('status', 60)->default('pending'); // publish : approve,  pending: waiting to approve, draft : join us
                $table->timestamps();

                $table->foreign('room_id')->references('id')->on('room')->onDelete('cascade');
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
        Schema::dropIfExists('room_member');
        Schema::dropIfExists('comments_egardens');
        Schema::dropIfExists('egardens');
        Schema::dropIfExists('room');
    }
}

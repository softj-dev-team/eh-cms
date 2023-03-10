<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCampusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('study_room_categories')) {
            Schema::create('study_room_categories', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 120)->nullable();
                $table->unsignedInteger('parent_id')->nullable();
                $table->string('background', 120)->nullable();
                $table->string('color', 120)->nullable();
                $table->string('status', 60)->default('publish');
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('study_room')) {
            Schema::create('study_room', function (Blueprint $table) {
                $table->increments('id');
                $table->string('title', 120)->nullable();
                $table->text('categories')->nullable();
                $table->text('contact')->nullable();
                $table->text('detail')->nullable();
                $table->unsignedInteger('member_id')->nullable();
                $table->text('images')->nullable();
                $table->unsignedInteger('lookup')->nullable();
                $table->string('status', 60)->default('publish');
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('study_room_comments')) {
            Schema::create('study_room_comments', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedInteger('study_room_id');
                $table->unsignedInteger('parents_id')->nullable();
                $table->string('nickname')->nullable();//nickname of member
                $table->text('content');
                $table->string('status', 60)->default('publish');
                $table->timestamps();
                $table->foreign('study_room_id')->references('id')->on('study_room')->onDelete('cascade');
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
        Schema::table('study_room_comments', function (Blueprint $table) {
            $table->dropForeign('study_room_comments_study_room_id_foreign');
        });
        
        Schema::dropIfExists('study_room_comments');
        Schema::dropIfExists('study_room_categories');
        Schema::dropIfExists('study_room');
    }
}

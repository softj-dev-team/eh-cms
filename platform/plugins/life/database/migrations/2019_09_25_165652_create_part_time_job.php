<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartTimeJob extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('jobs_categories')) {
            Schema::create('jobs_categories', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 120)->nullable();
                $table->unsignedInteger('parent_id')->nullable();
                $table->string('background', 120)->nullable();
                $table->string('color', 120)->nullable();
                $table->string('status', 60)->default('publish');
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('jobs_part_time')) {
            Schema::create('jobs_part_time', function (Blueprint $table) {
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
        if (!Schema::hasTable('jobs_comments')) {
            Schema::create('jobs_comments', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedInteger('jobs_part_time_id');
                $table->unsignedInteger('parents_id')->nullable();
                $table->string('user_email')->nullable();//nickname of member
                $table->text('content');

                $table->string('status', 60)->default('publish');
                $table->timestamps();
                $table->foreign('jobs_part_time_id')->references('id')->on('jobs_part_time')->onDelete('cascade');
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

        Schema::dropIfExists('jobs_categories');
        Schema::table('jobs_comments', function (Blueprint $table) {
            $table->dropForeign('jobs_comments_jobs_part_time_id_foreign');
        });
        
        Schema::dropIfExists('jobs_comments');
        Schema::dropIfExists('jobs_part_time');
    }
}

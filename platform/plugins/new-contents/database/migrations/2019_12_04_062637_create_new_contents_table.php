<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNewContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('categories_new_contents')) {
            Schema::create('categories_new_contents', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('status', 60)->default('publish');
                $table->timestamps();

            });
         }
        if (!Schema::hasTable('new_contents')) {
            Schema::create('new_contents', function (Blueprint $table) {
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
                $table->unsignedInteger('categories_new_contents_id')->default(0);

                $table->string('status', 60)->default('publish');
                $table->timestamps();

                $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
                $table->foreign('categories_new_contents_id')->references('id')->on('categories_new_contents_id')->onDelete('cascade');

            });
        }
        if (!Schema::hasTable('comments_new_contents')) {
            Schema::create('comments_new_contents', function (Blueprint $table) {
                //
                $table->bigIncrements('id');
                $table->unsignedInteger('new_contents_id');
                $table->unsignedInteger('parents_id')->nullable();
                $table->unsignedInteger('member_id')->nullable();
                $table->unsignedInteger('anonymous')->default('0'); //0: true show name, 1: false show 'Anonymous'
                $table->text('content');

                $table->string('status', 60)->default('publish');
                $table->timestamps();

                $table->foreign('new_contents_id')->references('id')->on('new_contents')->onDelete('cascade');
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
        Schema::dropIfExists('comments_new_contents');
        Schema::dropIfExists('new_contents');
        Schema::dropIfExists('categories_new_contents');
    }
}

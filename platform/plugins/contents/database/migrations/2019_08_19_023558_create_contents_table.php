<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories_contents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('status', 60)->default('publish');
            $table->timestamps();

            
        });
        Schema::create('contents', function (Blueprint $table) {
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

            $table->unsignedInteger('categories_contents_id')->default(0);
            $table->foreign('categories_contents_id')->references('id')->on('categories_contents')->onDelete('cascade');

            $table->string('status', 60)->default('publish');
            $table->timestamps();
        });

        
        Schema::create('comments_contents', function (Blueprint $table) {
            //
            $table->bigIncrements('id');
            $table->unsignedInteger('contents_id');
            $table->unsignedInteger('parents_id')->nullable();
            $table->string('user_email')->nullable();
            $table->text('content');

            $table->string('status', 60)->default('publish');
            $table->timestamps();
            $table->foreign('contents_id')->references('id')->on('contents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments_contents');
        Schema::dropIfExists('contents');
        Schema::dropIfExists('categories_contents');
       
        
    }
}

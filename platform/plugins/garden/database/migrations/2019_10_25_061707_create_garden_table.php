<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGardenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('categories_gardens')) {
            Schema::create('categories_gardens', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('status', 60)->default('publish');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('gardens')) {
            Schema::create('gardens', function (Blueprint $table) {
                $table->increments('id');
                $table->string('title')->nullable();
                $table->text('detail')->nullable();
                $table->text('notice')->nullable();
                $table->integer('lookup')->default(0);
                $table->integer('right_click')->default(0);
                $table->integer('active_empathy')->default(0);
                $table->unsignedInteger('member_id')->nullable();
                $table->unsignedInteger('categories_gardens_id')->nullable();
                $table->string('status', 60)->default('publish');
                $table->timestamps();

                $table->foreign('categories_gardens_id')->references('id')->on('categories_gardens')->onDelete('cascade');
                $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');

            });
        }

        if (!Schema::hasTable('comments_gardens')) {
            Schema::create('comments_gardens', function (Blueprint $table) {
                //
                $table->bigIncrements('id');
                $table->unsignedInteger('gardens_id');
                $table->unsignedInteger('parents_id')->nullable();
                $table->string('nickname')->nullable();
                $table->text('content');
    
                $table->string('status', 60)->default('publish');
                $table->timestamps();
                $table->foreign('gardens_id')->references('id')->on('gardens')->onDelete('cascade');
            });
        }  
        
        if (!Schema::hasTable('sympathy_gardens')) {
            Schema::create('sympathy_gardens', function (Blueprint $table) {
                //
                $table->bigIncrements('id');
                $table->unsignedInteger('gardens_id');
                $table->unsignedInteger('member_id')->nullable();

                $table->timestamps();
                $table->unique(['gardens_id', 'member_id']);
                $table->foreign('gardens_id')->references('id')->on('gardens')->onDelete('cascade');
                $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
            });
        }   

         
        if (!Schema::hasTable('popular_gardens')) {
            Schema::create('popular_gardens', function (Blueprint $table) {
                //
                $table->bigIncrements('id');
                $table->string('keyword');
                $table->unsignedInteger('lookup')->nullable();
                $table->unsignedInteger('categories_id')->nullable();

                $table->timestamps();
                $table->unique(['keyword', 'categories_id']);
                $table->foreign('categories_id')->references('id')->on('categories_gardens')->onDelete('cascade');
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
        Schema::dropIfExists('popular_gardens');
        Schema::dropIfExists('sympathy_gardens');
        Schema::dropIfExists('comments_gardens');
        Schema::dropIfExists('gardens');
        Schema::dropIfExists('categories_gardens');

    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColumnShelter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('shelter_categories')) {
            Schema::create('shelter_categories', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 120)->nullable();
                $table->unsignedInteger('parent_id')->nullable();
                $table->string('background', 120)->nullable();
                $table->string('color', 120)->nullable();
                $table->string('status', 60)->default('publish');
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('shelter')) {
            Schema::create('shelter', function (Blueprint $table) {
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
        if (!Schema::hasTable('shelter_comments')) {
            Schema::create('shelter_comments', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedInteger('shelter_id');
                $table->unsignedInteger('parents_id')->nullable();
                $table->string('nickname')->nullable();//nickname of member
                $table->text('content');
                $table->string('status', 60)->default('publish');
                $table->timestamps();
                $table->foreign('shelter_id')->references('id')->on('shelter')->onDelete('cascade');
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
        Schema::table('shelter_comments', function (Blueprint $table) {
            $table->dropForeign('shelter_comments_shelter_id_foreign');
        });
        
        Schema::dropIfExists('shelter_comments');
        Schema::dropIfExists('shelter_categories');
        Schema::dropIfExists('shelter');
    }
}

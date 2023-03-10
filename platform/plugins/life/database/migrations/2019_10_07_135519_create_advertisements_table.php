<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertisementsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('ads_categories')) {
            Schema::create('ads_categories', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 120)->nullable();
                $table->unsignedInteger('parent_id')->nullable();
                $table->string('background', 120)->nullable();
                $table->string('color', 120)->nullable();
                $table->string('status', 60)->default('publish');
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('advertisements')) {
            Schema::create('advertisements', function (Blueprint $table) {
                $table->increments('id');
                $table->string('title', 120)->nullable();
                $table->text('categories')->nullable();
                $table->text('details')->nullable();
                $table->dateTime('deadline')->nullable();
                $table->unsignedInteger('member_id')->nullable();
                $table->text('images')->nullable();
                $table->unsignedInteger('lookup')->nullable();
                $table->string('status', 60)->default('publish');
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('ads_comments')) {
            Schema::create('ads_comments', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedInteger('advertisements_id');
                $table->unsignedInteger('parents_id')->nullable();
                $table->string('nickname')->nullable();//nickname of member
                $table->text('content');

                $table->string('status', 60)->default('publish');
                $table->timestamps();
                $table->foreign('advertisements_id')->references('id')->on('advertisements')->onDelete('cascade');
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

        Schema::dropIfExists('ads_categories');
        Schema::table('ads_comments', function (Blueprint $table) {
            $table->dropForeign('ads_comments_advertisements_id_foreign');
        });
        
        Schema::dropIfExists('ads_comments');
        Schema::dropIfExists('advertisements');
    }
}

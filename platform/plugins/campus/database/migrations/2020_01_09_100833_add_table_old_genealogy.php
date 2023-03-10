<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableOldGenealogy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('old_genealogy')) {
            Schema::create('old_genealogy', function (Blueprint $table) {
                $table->increments('id');
                $table->string('title', 120)->nullable();
                $table->text('detail')->nullable();
                $table->unsignedInteger('member_id')->nullable();
                $table->text('images')->nullable();
                $table->unsignedInteger('lookup')->nullable();
                $table->string('status', 60)->default('publish');
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('old_genealogy_comments')) {
            Schema::create('old_genealogy_comments', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedInteger('old_genealogy_id');
                $table->unsignedInteger('parents_id')->nullable();
                $table->unsignedInteger('member_id')->nullable();
                $table->unsignedInteger('anonymous')->default('0'); //0: true show name, 1: false show 'Anonymous'
                $table->text('content');
                $table->string('status', 60)->default('publish');

                $table->foreign('old_genealogy_id')->references('id')->on('old_genealogy')->onDelete('cascade');
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
        Schema::dropIfExists('old_genealogy_comments');
        Schema::dropIfExists('old_genealogy');
    }
}

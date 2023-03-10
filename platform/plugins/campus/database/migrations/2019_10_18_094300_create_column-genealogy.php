<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColumnGenealogy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('genealogy')) {
            Schema::create('genealogy', function (Blueprint $table) {
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
        if (!Schema::hasTable('genealogy_comments')) {
            Schema::create('genealogy_comments', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedInteger('genealogy_id');
                $table->unsignedInteger('parents_id')->nullable();
                $table->string('nickname')->nullable();//nickname of member
                $table->text('content');
                $table->string('status', 60)->default('publish');
                $table->timestamps();
                $table->foreign('genealogy_id')->references('id')->on('genealogy')->onDelete('cascade');
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
        Schema::table('genealogy_comments', function (Blueprint $table) {
            $table->dropForeign('genealogy_comments_genealogy_id_foreign');
        });
        
        Schema::dropIfExists('genealogy_comments');
        Schema::dropIfExists('genealogy');
    }
}

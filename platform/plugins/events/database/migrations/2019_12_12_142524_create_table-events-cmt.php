<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEventsCmt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('events_cmt')) {
            Schema::create('events_cmt', function (Blueprint $table) {
                $table->increments('id');
                $table->string('title')->nullable();
                $table->text('detail')->nullable();
                $table->unsignedInteger('category_events_id')->default(0);
                $table->unsignedInteger('member_id')->nullable(); //Author
                $table->string('status', 60)->default('publish');
                $table->integer('views')->default(0);
                $table->timestamps();

                $table->foreign('category_events_id')->references('id')->on('category_events')->onDelete('cascade');
                $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
            });
        }
        if (!Schema::hasTable('comments_events_cmt')) {
            Schema::create('comments_events_cmt', function (Blueprint $table) {
                //
                $table->bigIncrements('id');
                $table->unsignedInteger('events_cmt_id');
                $table->unsignedInteger('parents_id')->nullable();
                $table->unsignedInteger('member_id')->nullable(); //author
                $table->unsignedInteger('anonymous')->default('0'); //0: true show name, 1: false show 'Anonymous'
                $table->text('content');
                $table->string('status', 60)->default('publish');
                $table->timestamps();

                $table->foreign('events_cmt_id')->references('id')->on('events_cmt')->onDelete('cascade');
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
        Schema::dropIfExists('comments_events_cmt');
        Schema::dropIfExists('events_cmt');
    }
}

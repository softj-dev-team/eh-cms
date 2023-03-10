<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableOpenSpace extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('open_space')) {
            Schema::create('open_space', function (Blueprint $table) {
                $table->increments('id');
                $table->string('title')->nullable();
                $table->text('detail')->nullable();
                $table->unsignedInteger('member_id')->nullable(); //Author
                $table->string('status', 60)->default('publish');
                $table->integer('views')->default(0);
                $table->timestamps();

                $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
            });
        }
        if (!Schema::hasTable('open_space_comments')) {
            Schema::create('open_space_comments', function (Blueprint $table) {
                //
                $table->bigIncrements('id');
                $table->unsignedInteger('open_space_id');
                $table->unsignedInteger('parents_id')->nullable();
                $table->unsignedInteger('member_id')->nullable(); //author
                $table->unsignedInteger('anonymous')->default('0'); //0: true show name, 1: false show 'Anonymous'
                $table->text('content');
                $table->string('status', 60)->default('publish');
                $table->timestamps();

                $table->foreign('open_space_id')->references('id')->on('open_space')->onDelete('cascade');
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
        Schema::dropIfExists('open_space_comments');
        Schema::dropIfExists('open_space');
    }
}

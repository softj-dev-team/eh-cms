<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableSympathyOpenSpace extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sympathy_open_space', function (Blueprint $table) {
            //
            $table->bigIncrements('id');
            $table->unsignedInteger('open_space_id');
            $table->unsignedInteger('member_id')->nullable();
            $table->unsignedInteger('is_dislike')->default(0);//0 : like, 1: dislike

            $table->timestamps();
            $table->unique(['open_space_id', 'member_id']);
            $table->foreign('open_space_id')->references('id')->on('open_space')->onDelete('cascade');
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sympathy_open_space');
    }
}

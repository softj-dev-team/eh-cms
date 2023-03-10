<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableSympathyShelter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sympathy_shelter', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('shelter_id');
            $table->unsignedInteger('member_id')->nullable();
            $table->unsignedInteger('is_dislike')->default(0);//0 : like, 1: dislike

            $table->timestamps();
            $table->unique(['shelter_id', 'member_id']);
            $table->foreign('shelter_id')->references('id')->on('shelter')->onDelete('cascade');
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
        Schema::dropIfExists('sympathy_shelter');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSympathyEgarden extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sympathy_egardens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('egardens_id');
            $table->unsignedInteger('member_id')->nullable();
            $table->unsignedInteger('is_dislike')->default(0);//0 : like, 1: dislike
            $table->text('reason')->nullable();

            $table->timestamps();
            $table->unique(['egardens_id', 'member_id']);
            $table->foreign('egardens_id')->references('id')->on('egardens')->onDelete('cascade');
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
        Schema::dropIfExists('sympathy_egardens');
    }
}

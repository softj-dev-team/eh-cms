<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableSympathyJobsPartTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sympathy_jobs_part_time', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('jobs_part_time_id');
            $table->unsignedInteger('member_id')->nullable();
            $table->unsignedInteger('is_dislike')->default(0);//0 : like, 1: dislike

            $table->timestamps();
            $table->unique(['jobs_part_time_id', 'member_id']);
            $table->foreign('jobs_part_time_id')->references('id')->on('jobs_part_time')->onDelete('cascade');
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
        Schema::dropIfExists('sympathy_jobs_part_time');
    }
}

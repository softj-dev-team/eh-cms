<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSympathyJobsPartTimeComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sympathy_jobs_comments', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedInteger('post_id');
            $table->unsignedBigInteger('comments_id');
            $table->unsignedInteger('member_id')->nullable();
            $table->unsignedInteger('is_dislike')->default(0);//0 : no like- no dislike, 1: dislike
            $table->text('reason')->nullable();
            $table->timestamps();

            $table->unique(['post_id', 'member_id','comments_id'],'sympathy_comments_jobs_part_time_id_member_id_unique');
            $table->foreign('post_id')->references('id')->on('jobs_part_time')->onDelete('cascade');
            $table->foreign('comments_id')->references('id')->on('jobs_comments')->onDelete('cascade');
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
        Schema::dropIfExists('sympathy_jobs_comments');
    }
}

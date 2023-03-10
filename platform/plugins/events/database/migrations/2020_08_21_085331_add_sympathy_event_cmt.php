<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSympathyEventCmt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sympathy_eventcmt_comment', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedInteger('ecmt_id');
            $table->unsignedBigInteger('ecmt_comments_id');
            $table->unsignedInteger('member_id')->nullable();
            $table->unsignedInteger('is_dislike')->default(0);//0 : no like- no dislike, 1: dislike
            $table->text('reason')->nullable();
            $table->timestamps();

            $table->unique(['ecmt_id', 'member_id','ecmt_comments_id'],'sympathy_ecmt_comment_ecmt_id_member_id_ecmt_comments_id_unique');
            $table->foreign('ecmt_id')->references('id')->on('events_cmt')->onDelete('cascade');
            $table->foreign('ecmt_comments_id')->references('id')->on('comments_events_cmt')->onDelete('cascade');
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
        Schema::dropIfExists('sympathy_eventcmt_comment');
    }
}

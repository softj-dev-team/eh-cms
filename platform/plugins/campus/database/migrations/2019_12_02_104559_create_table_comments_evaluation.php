<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCommentsEvaluation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        if (!Schema::hasTable('comments_evaluation')) {
            Schema::create('comments_evaluation', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('evaluation_id');
                $table->unsignedInteger('votes');
                $table->unsignedInteger('member_id')->nullable();
                $table->string('comments')->nullable();

                $table->foreign('evaluation_id')->references('id')->on('evaluation')->onDelete('cascade');
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
        Schema::dropIfExists('comments_evaluation');
    }
}

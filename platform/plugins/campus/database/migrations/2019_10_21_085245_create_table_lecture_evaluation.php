<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLectureEvaluation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('evaluation')) {
            Schema::create('evaluation', function (Blueprint $table) {
                $table->increments('id');
                $table->string('title', 120)->nullable();
                $table->string('professor_name', 120)->nullable();
                $table->text('semester')->nullable();
                $table->string('score', 120)->nullable();
                $table->text('major')->nullable();
                $table->string('grade', 120)->nullable();
                $table->string('remark', 120)->nullable();
                $table->unsignedInteger('lookup')->nullable();
                $table->string('status', 60)->default('publish');
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
        Schema::dropIfExists('evaluation');
    }
}

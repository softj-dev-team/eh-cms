<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableNoticesCampus extends Migration
{
/**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('notices_campus')) {
            Schema::create('notices_campus', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->nullable();
                $table->string('code', 120)->nullable();
                $table->text('notices')->nullable();
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
        Schema::dropIfExists('notices_campus');
    }
}

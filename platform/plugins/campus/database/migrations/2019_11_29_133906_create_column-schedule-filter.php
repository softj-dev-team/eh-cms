<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColumnScheduleFilter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('schedule_filter')) {
            Schema::create('schedule_filter', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 120)->nullable();
                $table->dateTime('start')->nullable();
                $table->dateTime('end')->nullable();
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
        Schema::dropIfExists('schedule_filter');
    }
}

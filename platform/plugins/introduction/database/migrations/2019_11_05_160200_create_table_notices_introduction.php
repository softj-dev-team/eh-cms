<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableNoticesIntroduction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('notices_introduction')) {
            Schema::create('notices_introduction', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->nullable();
                $table->text('notices')->nullable();
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
        Schema::dropIfExists('notices_introduction');
    }
}

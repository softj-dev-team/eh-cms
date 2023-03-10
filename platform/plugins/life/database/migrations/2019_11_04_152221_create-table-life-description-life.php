<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableLifeDescriptionLife extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('description_life')) {
            Schema::create('description_life', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->nullable();
                $table->string('code', 120)->nullable();
                $table->text('description')->nullable();
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
        Schema::dropIfExists('description_life');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDescriptionGardens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('description_gardens')) {
            Schema::create('description_gardens', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->nullable();
                $table->unsignedInteger('categories_gardens_id')->nullable();
                $table->text('description')->nullable();
                $table->string('status', 60)->default('publish');
                $table->timestamps();

                $table->foreign('categories_gardens_id')->references('id')->on('categories_gardens')->onDelete('cascade');
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
        Schema::dropIfExists('description_gardens');
    }
}

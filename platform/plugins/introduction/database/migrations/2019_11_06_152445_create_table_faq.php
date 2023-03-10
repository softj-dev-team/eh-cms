<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableFaq extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('faq_categories')) {
            Schema::create('faq_categories', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 120)->nullable();
                $table->string('status', 60)->default('publish');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('faq_introduction')) {
            Schema::create('faq_introduction', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('faq_categories_id');
                $table->text('question')->nullable();
                $table->text('answer')->nullable();
                $table->string('status', 60)->default('publish');
                $table->timestamps();

                $table->foreign('faq_categories_id')->references('id')->on('faq_categories')->onDelete('cascade');
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
        Schema::dropIfExists('faq_introduction');
        Schema::dropIfExists('faq_categories');
        
    }
}

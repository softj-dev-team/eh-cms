<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLifeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('notices')) {
            Schema::create('notices', function (Blueprint $table) {
                $table->increments('id');
                $table->string('code', 120)->nullable();
                $table->text('notices')->nullable();
                $table->string('status', 60)->default('publish');
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('categories_market')) {
            Schema::create('categories_market', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 120)->nullable();
                $table->unsignedInteger('parent_id')->nullable();
                $table->string('background', 120)->nullable();
                $table->string('color', 120)->nullable();
                $table->string('status', 60)->default('publish');
                $table->timestamps();
  
                $table->foreign('parent_id')->references('id')->on('categories_market');
            });
        }
        if (!Schema::hasTable('flare_market')) {
            Schema::create('flare_market', function (Blueprint $table) {
                $table->increments('id');
                $table->string('title', 120)->nullable();
                $table->text('categories')->nullable();
                $table->string('purchasing_price', 120)->nullable();
                $table->string('reason_selling', 120)->nullable();
                $table->string('sale_price', 120)->nullable();
                $table->text('exchange')->nullable();
                $table->text('contact')->nullable();
                $table->text('detail')->nullable();
                $table->unsignedInteger('member_id')->nullable();
                $table->text('images')->nullable();
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
        Schema::table('categories_market', function (Blueprint $table) {
            //
            $table->dropForeign('categories_market_parent_id_foreign');
        });

        Schema::dropIfExists('notices');
        Schema::dropIfExists('categories_market');
        Schema::dropIfExists('flare_market');
    }
}

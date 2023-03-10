<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableCategoriesIntroduction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('categories_introductions')) {
            Schema::create('categories_introductions', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 120)->nullable();
                $table->string('status', 60)->default('publish');
                $table->timestamps();
            });
        }

        Schema::table('introductions', function (Blueprint $table) {
            //
            $table->text('link', 120)->nullable()->change();
            $table->unsignedInteger('categories_introductions_id')->nullable();

            $table->foreign('categories_introductions_id')->references('id')->on('categories_introductions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('introductions', function (Blueprint $table) {

            $table->dropForeign('introductions_categories_introductions_id_foreign');

            $table->dropColumn('categories_introductions_id');
            $table->text('link', 120)->nullable(false)->change();
        });
        Schema::dropIfExists('categories_introductions');
    }
}

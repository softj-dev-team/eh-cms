<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMajor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evaluation', function (Blueprint $table) {
            //
            $table->dropColumn('major');
        });

        if (!Schema::hasTable('major')) {
            Schema::create('major', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->nullable();
                $table->string('status', 60)->default('publish');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('major_evaluation')) {
            Schema::create('major_evaluation', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('evaluation_id');
                $table->unsignedInteger('major_id');
                $table->string('status', 60)->default('publish');
                $table->timestamps();

                $table->foreign('evaluation_id')->references('id')->on('evaluation')->onDelete('cascade');
                $table->foreign('major_id')->references('id')->on('major')->onDelete('cascade');
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
        Schema::table('evaluation', function (Blueprint $table) {
            //
            $table->text('major')->nullable();
        });
        Schema::dropIfExists('major_evaluation');
        Schema::dropIfExists('major');
    }
}

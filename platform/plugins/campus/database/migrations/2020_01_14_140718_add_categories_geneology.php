<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCategoriesGeneology extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('genealogy', function (Blueprint $table) {
            //
            $table->dropColumn('title');

            $table->text('semester_year')->nullable();
            $table->text('semester_session')->nullable();
            $table->text('class_name')->nullable();
            $table->text('professor_name')->nullable();
            $table->text('exam_name')->nullable();
            $table->text('file_upload')->nullable();
            $table->text('link')->nullable();
        });

        if (!Schema::hasTable('major_genealogy')) {
            Schema::create('major_genealogy', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('genealogy_id');
                $table->unsignedInteger('major_id');
                $table->string('status', 60)->default('publish');
                $table->timestamps();

                $table->foreign('genealogy_id')->references('id')->on('genealogy')->onDelete('cascade');
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
        Schema::table('genealogy', function (Blueprint $table) {
            //
            $table->text('title')->nullable();

            $table->dropColumn('semester_year');
            $table->dropColumn('semester_session');
            $table->dropColumn('class_name');
            $table->dropColumn('professor_name');
            $table->dropColumn('exam_name');
            $table->text('file_upload')->nullable();
            $table->text('link')->nullable();

        });

        Schema::dropIfExists('major_genealogy');
    }
}

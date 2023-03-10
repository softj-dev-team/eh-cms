<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalculator extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calculator', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->unsignedInteger('member_id');
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
        });
        Schema::create('calculator_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('classification');
            $table->text('description');
            $table->unsignedInteger('point');
            $table->float('grades');
            $table->unsignedBigInteger('id_calculator');
            $table->unsignedInteger('group');
            $table->timestamps();

            $table->foreign('id_calculator')->references('id')->on('calculator')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calculator_detail');
        Schema::dropIfExists('calculator');
    }
}

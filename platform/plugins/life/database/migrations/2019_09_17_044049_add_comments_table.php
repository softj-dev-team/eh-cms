<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flare_comments', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->unsignedInteger('flare_id');
            $table->unsignedInteger('parents_id')->nullable();
            $table->string('user_email')->nullable();//nickname of member
            $table->text('content');

            $table->string('status', 60)->default('publish');
            $table->timestamps();
            $table->foreign('flare_id')->references('id')->on('flare_market')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('flare_comments', function (Blueprint $table) {
            //
            $table->dropForeign('flare_comments_flare_id_foreign');
        });
        Schema::dropIfExists('flare_comments');
    }
}

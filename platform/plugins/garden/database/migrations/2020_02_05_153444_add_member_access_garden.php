<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMemberAccessGarden extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('access_garden')) {
            Schema::create('access_garden', function (Blueprint $table) {

                $table->increments('id');
                $table->unsignedInteger('member_id')->nullable();
                $table->dateTime('time_access_from')->nullable();
                $table->dateTime('time_access_to')->nullable();
                $table->string('status', 60)->default('publish');
                $table->timestamps();

                $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
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
        Schema::dropIfExists('access_garden');
    }
}

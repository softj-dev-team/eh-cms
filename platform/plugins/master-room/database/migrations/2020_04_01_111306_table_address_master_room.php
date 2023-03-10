<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableAddressMasterRoom extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address_master_rooms', function (Blueprint $table) {
            //
            $table->bigIncrements('id');
            $table->string('classification')->nullable();
            $table->string('email')->nullable();
            $table->text('home_page')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('address')->nullable();
            $table->string('home_phone')->nullable();
            $table->string('mobile_phone')->nullable();
            $table->string('company_phone')->nullable();
            $table->timestamp('published')->nullable();
            $table->string('memo')->nullable();
            $table->unsignedInteger('member_id')->nullable();
            $table->string('status', 60)->default('publish');
            $table->timestamps();
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
            Schema::dropIfExists('address_master_rooms');
    }
}

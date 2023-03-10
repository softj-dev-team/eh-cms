<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMemberNotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('member_from_id')->nullable();
            $table->unsignedInteger('member_to_id')->nullable(); 
            $table->text('contents');
            
            $table->string('status')->default('unread');
            $table->timestamps();

            $table->foreign('member_from_id')->references('id')->on('members')->onDelete('cascade');
            $table->foreign('member_to_id')->references('id')->on('members')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_notes');
    }
}

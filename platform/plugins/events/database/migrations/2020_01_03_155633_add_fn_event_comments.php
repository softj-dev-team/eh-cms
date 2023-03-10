<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFnEventComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            //
            if (Schema::hasColumn('comments', 'user_email'))
            {
                $table->dropColumn('user_email');
            }
            $table->unsignedInteger('member_id')->nullable();
            $table->unsignedInteger('anonymous')->default('0'); //0: true show name, 1: false show 'Anonymous'

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
        Schema::table('comments', function (Blueprint $table) {
            //
            $table->string('user_email')->nullable();
            $table->dropForeign('comments_member_id_foreign');
            $table->dropColumn('member_id');
            $table->dropColumn('anonymous');
        });
    }
}

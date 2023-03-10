<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFnShelterComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shelter_comments', function (Blueprint $table) {
            if (Schema::hasColumn('shelter_comments', 'nickname'))
            {
                $table->dropColumn('nickname');
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
        Schema::table('shelter_comments', function (Blueprint $table) {

            $table->dropForeign('shelter_comments_member_id_foreign');
            $table->string('nickname')->nullable();
            $table->dropColumn('member_id');
            $table->dropColumn('anonymous');
        });
    }
}

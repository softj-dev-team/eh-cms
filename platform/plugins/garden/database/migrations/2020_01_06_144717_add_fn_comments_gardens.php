<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFnCommentsGardens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comments_gardens', function (Blueprint $table) {
            if (Schema::hasColumn('comments_gardens', 'nickname'))
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
        Schema::table('comments_gardens', function (Blueprint $table) {
            //
            $table->dropForeign('comments_gardens_member_id_foreign');
            $table->string('nickname')->nullable();
            $table->dropColumn('member_id');
            $table->dropColumn('anonymous');
        });
    }
}

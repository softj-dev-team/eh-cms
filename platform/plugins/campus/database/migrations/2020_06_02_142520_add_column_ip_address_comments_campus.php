<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIpAddressCommentsCampus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('study_room_comments', function (Blueprint $table) {
            //
            $table->text('ip_address')->nullable();
        });
        Schema::table('old_genealogy_comments', function (Blueprint $table) {
            //
            $table->text('ip_address')->nullable();
        });
        Schema::table('genealogy_comments', function (Blueprint $table) {
            //
            $table->text('ip_address')->nullable();
        });
        Schema::table('comments_evaluation', function (Blueprint $table) {
            //
            $table->text('ip_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('study_room_comments', function (Blueprint $table) {
            //
            $table->dropColumn('ip_address');
        });
        Schema::table('old_genealogy_comments', function (Blueprint $table) {
            //
            $table->dropColumn('ip_address');
        });
        Schema::table('genealogy_comments', function (Blueprint $table) {
            //
            $table->dropColumn('ip_address');
        });
        Schema::table('comments_evaluation', function (Blueprint $table) {
            //
            $table->dropColumn('ip_address');
        });
    }
}

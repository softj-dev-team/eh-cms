<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->unsignedBigInteger('member_id');
            $table->tinyInteger('site_notice')->default(0);
            $table->tinyInteger('eh_content')->default(0);
            $table->tinyInteger('bulletin_comment_on_post')->default(0);
            $table->tinyInteger('bulletin_comment_on_comment')->default(0);
            $table->tinyInteger('secret_garden_comment_on_post')->default(0);
            $table->tinyInteger('secret_garden_comment_on_comment')->default(0);
            $table->tinyInteger('garden_notice')->default(0);
            $table->tinyInteger('garden_new_post')->default(0);
            $table->tinyInteger('garden_comment_on_post')->default(0);
            $table->tinyInteger('garden_comment_on_comment')->default(0);
            $table->tinyInteger('message_notification')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_settings');
    }
}

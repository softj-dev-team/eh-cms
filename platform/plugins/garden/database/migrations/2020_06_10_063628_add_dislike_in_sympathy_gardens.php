<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDislikeInSympathyGardens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sympathy_gardens', function (Blueprint $table) {
            //
            $table->unsignedInteger('is_dislike')->default(0);//0 : like, 1: dislike
            $table->text('reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sympathy_gardens', function (Blueprint $table) {
            //
            $table->dropColumn('reason');
            $table->dropColumn('is_dislike');
        });
    }
}

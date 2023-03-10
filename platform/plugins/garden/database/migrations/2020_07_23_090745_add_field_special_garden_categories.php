<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldSpecialGardenCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories_gardens', function (Blueprint $table) {
            //
            $table->unsignedInteger('special_garden')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories_gardens', function (Blueprint $table) {
            //
            $table->dropColumn('special_garden');
        });
    }
}

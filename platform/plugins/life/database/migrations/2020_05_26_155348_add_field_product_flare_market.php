<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldProductFlareMarket extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('flare_market', function (Blueprint $table) {
            //
            $table->timestamp('purchase_date')->nullable();
            $table->text('purchase_location')->nullable();
            $table->string('quality')->nullable();
            $table->string('product')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('flare_market', function (Blueprint $table) {
            //
            $table->dropColumn('product');
            $table->dropColumn('quality');
            $table->dropColumn('purchase_location');
            $table->dropColumn('purchase_date');
        });
    }
}

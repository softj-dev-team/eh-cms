<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldDepositeShelter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shelter', function (Blueprint $table) {
            //
            $table->text('deposit')->nullable();
            $table->text('monthly_rent')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shelter', function (Blueprint $table) {
            //
            $table->dropColumn('monthly_rent');
            $table->dropColumn('deposit');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTypeMemberBlacklist extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_blacklist', function (Blueprint $table) {
            $table->unsignedInteger('member_id')->charset('')->collation('')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_blacklist', function (Blueprint $table) {
            $table->string('member_id')->nullable()->change();
        });
    }
}


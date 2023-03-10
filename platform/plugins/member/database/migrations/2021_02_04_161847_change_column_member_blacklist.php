<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnMemberBlacklist extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_blacklist', function (Blueprint $table) {
            $table->renameColumn('mem_num', 'member_id');
            $table->dropColumn(['reg_date']);
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
            $table->renameColumn('member_id', 'mem_num');
            $table->string('reg_date')->nullable();
        });
    }
}


<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('members', function (Blueprint $table) {
            //
            $table->string('fullname', 120)->nullable();
            $table->string('namemail', 120)->nullable();
            $table->string('domainmail', 120)->nullable();
            $table->string('nickname')->nullable();
            $table->string('id_login', 120)->unique();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            //
            $table->dropColumn(['fullname', 'namemail', 'domainmail','nickname']);
        });
    }
}

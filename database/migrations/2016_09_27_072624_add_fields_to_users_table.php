<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->renameColumn('name', 'firstname');
            $table->string('middlename');
            $table->string('lastname');
            $table->string('role');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->renameColumn('firstname', 'name');
            $table->dropColumn('middlename');
            $table->dropColumn('lastname');
            $table->dropColumn('role');
            $table->dropSoftDeletes();
        });
    }
}

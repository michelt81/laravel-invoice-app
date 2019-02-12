<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCountryIdToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->integer('country_id')->unsigned()->default(528)->after('email');

            Schema::disableForeignKeyConstraints();
            $table->foreign('country_id')->references('id')->on('countries');
            Schema::enableForeignKeyConstraints();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            Schema::disableForeignKeyConstraints();
            $table->dropForeign(['country_id']);
            Schema::enableForeignKeyConstraints();
            $table->dropColumn(['country_id']);
        });
    }
}

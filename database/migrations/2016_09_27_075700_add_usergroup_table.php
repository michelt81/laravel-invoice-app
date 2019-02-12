<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsergroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usergroups', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('company');
            $table->string('logo');
            $table->string('street');
            $table->string('street_number');
            $table->string('postal_code');
            $table->string('city');
            $table->string('email');
            $table->string('phone');
            $table->string('fax');
            $table->string('mobile_phone');
            $table->string('iban');
            $table->string('vat_number');
            $table->string('kvk');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('users', function ($table) {
            $table->integer('usergroup_id')->unsigned();
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
            $table->dropColumn('usergroup_id');
        });

        Schema::drop('usergroups');
    }
}

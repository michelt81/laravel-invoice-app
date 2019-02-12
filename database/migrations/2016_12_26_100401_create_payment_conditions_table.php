<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_conditions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('usergroup_id')->unsigned();
            $table->foreign('usergroup_id')->references('id')->on('usergroups');
            $table->string('name');
            $table->string('type');
            $table->integer('value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('payment_conditions');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColorFieldsToUsergroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('usergroups', function ($table) {
            $table->string('primary_color');
            $table->string('secondary_color');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('usergroups', function ($table) {
            $table->dropColumn('primary_color');
            $table->dropColumn('secondary_color');
        });
    }
}

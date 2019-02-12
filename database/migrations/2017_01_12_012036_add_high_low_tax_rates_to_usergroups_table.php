<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHighLowTaxRatesToUsergroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('usergroups', function (Blueprint $table) {
            $table->decimal('tax_low', 5, 2)->after('company')->default(6);
            $table->decimal('tax_high', 5, 2)->after('tax_low')->default(21);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('usergroups', function (Blueprint $table) {
            $table->dropColumn(['tax_low', 'tax_high']);
        });
    }
}

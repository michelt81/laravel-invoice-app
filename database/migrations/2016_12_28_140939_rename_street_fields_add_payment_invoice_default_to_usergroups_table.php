<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameStreetFieldsAddPaymentInvoiceDefaultToUsergroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('usergroups', function (Blueprint $table) {
            $table->renameColumn('street', 'address');
            $table->renameColumn('street_number', 'address2');
            $table->integer('invoice_start');
            $table->integer('invoice_condition_days');
            $table->integer('invoice_condition_reminder');
            $table->string('country');
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
            $table->renameColumn('address', 'street');
            $table->renameColumn('address2', 'street_number');
            $table->dropColumn('invoice_start');
            $table->dropColumn('invoice_condition_days');
            $table->dropColumn('invoice_condition_reminder');
            $table->dropColumn('country');
        });
    }
}

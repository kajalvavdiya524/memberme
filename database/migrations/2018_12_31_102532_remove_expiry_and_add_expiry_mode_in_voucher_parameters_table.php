<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveExpiryAndAddExpiryModeInVoucherParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('voucher_parameters', function (Blueprint $table) {
            $table->dropColumn('expiry');
            $table->string('expiry_mode')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('voucher_parameters', function (Blueprint $table) {
            $table->string('expiry')->nullable();
            $table->dropColumn('expiry_mode');
        });
    }
}

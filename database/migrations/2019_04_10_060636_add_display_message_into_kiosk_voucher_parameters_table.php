<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDisplayMessageIntoKioskVoucherParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kiosk_voucher_parameters', function (Blueprint $table) {
            $table->string('voucher_message')->nullable();
            $table->integer('lighting')->nullable()->default(\App\base\IStatus::ACTIVE);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kiosk_voucher_parameters', function (Blueprint $table) {
            $table->dropColumn('voucher_message','lighting');
        });
    }
}

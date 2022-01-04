<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrganizationIdInKioskVoucherParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kiosk_voucher_parameters', function (Blueprint $table) {
            $table->unsignedInteger('organization_id')->nullable();
            $table->foreign('organization_id','organization_foreign_key_index_in_kiosks_voucher_parameters')->references('id')->on('organizations')->onDelete('no action');
            $table->integer('status')->nullable()->default(\App\base\IStatus::ACTIVE);
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
            $table->dropForeign('organization_foreign_key_index_in_kiosks_voucher_parameters');
            $table->dropColumn('organization_id','status');
        });
    }
}

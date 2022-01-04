<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrganizationIdInVoucherLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('voucher_logs', function (Blueprint $table) {
            $table->unsignedInteger('organization_id')->nullable();
            $table->foreign('organization_id','voucher_logs_organization_foreign_key')->references('id')->on('organizations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('voucher_logs', function (Blueprint $table) {
            $table->dropForeign('voucher_logs_organization_foreign_key');
            $table->dropColumn('organization_id');
        });
    }
}

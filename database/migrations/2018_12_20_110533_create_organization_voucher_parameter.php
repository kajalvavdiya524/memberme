<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationVoucherParameter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('organization_voucher_parameter', function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('voucher_parameter_id');
            $table->unsignedInteger('organization_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('organization_voucher_parameter');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('voucher_parameter_id');
            $table->unsignedInteger('organization_id');
            $table->string('voucher_code')->nullable();
            $table->integer('status')->nullable();
            $table->integer('organization')->nullable();
            $table->string('voucher_name')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->dateTime('purchase_date')->nullable();
            $table->string('voucher_value')->nullable();
            $table->integer('voucher_balance')->nullable();
            $table->dateTime('expiry_date')->nullable();
            $table->integer('allowed_validations')->nullable();
            $table->integer('validations_made')->nullable();
            $table->dateTime('last_validations')->nullable();
            $table->integer('validations_left')->nullable();
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
        Schema::dropIfExists('vouchers');
    }
}

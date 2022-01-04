<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVoucherParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voucher_parameters', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('voucher_type')->nullable();
            $table->string('promo_id')->nullable();
            $table->string('voucher_name')->nullable();
            $table->integer('multisite')->nullable();
            $table->integer('multisite_organizations')->nullable();
//            $table->integer('size')->nullable();
//            $table->integer('size_cost')->nullable();
            $table->string('voucher_code')->nullable();
            $table->integer('expires')->nullable();
            $table->string('expiry')->nullable();
            $table->integer('expiry_period_quantity')->nullable();
            $table->string('expiry_period_duration')->nullable();
            $table->dateTime('expiry_date')->nullable();
            $table->integer('uses')->nullable();
            $table->integer('limited_quantity')->nullable();
            $table->integer('availability')->nullable();
            $table->integer('min_value')->nullable();
            $table->integer('max_value')->nullable();
            $table->integer('value')->nullable();   // 1- Static Value, 2- Variable
            $table->integer('value_mode')->nullable();
            $table->string('voucher_front_image')->nullable();
            $table->string('voucher_back_image')->nullable();
            $table->integer('status')->nullable()   ;
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
        Schema::dropIfExists('voucher_parameters');
    }
}

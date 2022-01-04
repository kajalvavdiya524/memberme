<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKioskVoucherParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kiosk_voucher_parameters', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('voucher_parameter_id');
            $table->foreign('voucher_parameter_id')->references('id')->on('voucher_parameters')->onDelete('no action');
            $table->smallInteger('frequency')->nullable();
            $table->smallInteger('duration')->nullable();
            $table->smallInteger('days_before')->nullable();
            $table->smallInteger('days_after')->nullable();
            $table->smallInteger('kiosk_print')->default(\App\base\IStatus::ACTIVE);
            $table->smallInteger('email_voucher')->default(\App\base\IStatus::INACTIVE);
            $table->smallInteger('show_in_app')->default(\App\base\IStatus::ACTIVE);
            $table->string('display_message')->nullable();
            $table->string('sound')->nullable();
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
        Schema::dropIfExists('kiosk_voucher_parameters');
    }
}

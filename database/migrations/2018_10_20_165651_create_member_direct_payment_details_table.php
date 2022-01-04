<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberDirectPaymentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_direct_payment_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('member_id');
            $table->foreign('member_id','memberDirectPaymentMembersForeignKey')->references('id')->on('members')->onDelete('cascade');
            $table->double('payment_amount')->nullable();
            $table->double('payments_to_make')->nullable();
            $table->double('payments_remaining')->nullable();
            $table->dateTime('next_payment_date')->nullable();
            $table->double('owing')->nullable();
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
        Schema::dropIfExists('member_direct_payment_details');
    }
}

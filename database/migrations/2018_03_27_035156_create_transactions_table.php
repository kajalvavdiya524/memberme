<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('payment_type',['cash','eftpos', 'other'])->nullable();
            $table->integer('status')->nullable();
            $table->unsignedInteger('organization_id')->nullable();
            $table->unsignedInteger('receipt_id')->nullable();
            $table->unsignedInteger('payer_member_id')->nullable();
            $table->integer('total')->nullable();
            $table->dateTime('expiry_date_time')->nullable();
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
        Schema::dropIfExists('transactions');
    }
}

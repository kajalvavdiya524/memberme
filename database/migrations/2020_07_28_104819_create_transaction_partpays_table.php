<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionPartpaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_partpays', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('payment_type_id')->nullable();
            $table->foreign('payment_type_id','PaymentTypeForiegnKeyOnTransactionPartPays')->references('id')->on('payment_types')->onDelete('no action');

            $table->unsignedInteger('transaction_id')->nullable();
            $table->foreign('transaction_id','TransactionForiegnKeyOnTransactionPartPays')->references('id')->on('transactions')->onDelete('no action');

            $table->float('amount')->nullable();
            $table->float('owing_amount')->nullable();

            $table->float('status')->nullable()->default(\App\base\IStatus::ACTIVE);
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
        Schema::dropIfExists('transaction_partpays');
    }
}

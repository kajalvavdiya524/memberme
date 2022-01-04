<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_invoices', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('organization_id');
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('no action');

            $table->unsignedInteger('plan_id');
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('no action');


            $table->dateTime('period_start')->nullable();
            $table->dateTime('period_end')->nullable();

            $table->float('amount_due')->nullable();
            $table->float('amount_paid')->nullable();
            $table->float('amount_remaining')->nullable();
            $table->float('tax')->nullable();
            $table->float('total')->nullable();

            $table->float('application_fee')->nullable();
            $table->integer('attempt_count')->nullable();
            $table->boolean('attempted')->nullable();
            $table->boolean('auto_advance')->nullable();
            $table->string('billing')->nullable();
            $table->boolean('closed')->nullable();
            $table->string('currency')->nullable();
            $table->string('customer')->nullable();
            $table->dateTime('date')->nullable();
            $table->float('ending_balance')->nullable();

            $table->dateTime('finalized_at')->nullable();
            $table->dateTime('forgiven')->nullable();
            $table->text('lines')->nullable();
            $table->string('number')->nullable();
            $table->string('paid')->nullable();
            $table->string('receipt_number')->nullable();
            $table->string('status')->nullable();
            $table->string('subscription')->nullable();

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
        Schema::dropIfExists('user_invoices');
    }
}

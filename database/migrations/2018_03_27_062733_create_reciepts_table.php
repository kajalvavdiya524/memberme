<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecieptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('due_date')->nullable();
            $table->unsignedInteger('organization_id')->nullable();
            $table->bigInteger('receipt_no')->nullable();
            $table->unsignedInteger('payer_member_id')->nullable();
            $table->float('sub_total')->nullable();
            $table->float('gst')->default(15)->nullable();
            $table->float('total')->nullable();
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
        Schema::dropIfExists('receipts');
    }
}

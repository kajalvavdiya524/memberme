<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStripeSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stripe_subscriptions', function (Blueprint $table) {
            $table->increments('id');


            $table->unsignedInteger('organization_id');
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('no action');

            $table->unsignedInteger('plan_id');
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('no action');


            $table->dateTime('current_period_end')->nullable();
            $table->dateTime('current_period_start')->nullable();
            $table->string('customer')->nullable();
            $table->integer('days_until_due')->nullable();
            $table->string('default_source')->nullable();
            $table->float('discount')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->boolean('has_more')->nullable();
            $table->integer('total_count')->nullable();
            $table->integer('quantity')->nullable();
            $table->timestamp('start')->nullable();
            $table->string('status')->nullable();
            $table->float('tax_percent')->nullable();
            $table->string('ref_id')->nullable();

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
        Schema::dropIfExists('stripe_subscriptions');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('organization_id');
            $table->string('title')->nullable();

            $table->float('joining_fee')->nullable();
            $table->float('subscription_fee')->nullable();

            $table->integer('expires')->nullable()->default(\App\base\IStatus::ACTIVE);
            $table->integer('expiry_duration')->nullable();
            $table->string('expiry_term')->nullable();
            $table->string('expiry')->nullable();

            $table->integer('overdue')->nullable()->default(\App\base\IStatus::INACTIVE);
            $table->integer('overdue_duration')->nullable();
            $table->string('overdue_term')->nullable();

            $table->integer('pro_rata')->nullable()->default(\App\base\IStatus::INACTIVE);
            $table->integer('amount')->nullable();
            $table->string('frequency')->nullable();

            $table->integer('late_payment')->nullable()->default(\App\base\IStatus::INACTIVE);
            $table->integer('late_payment_duration')->nullable();
            $table->string('late_payment_term')->nullable();
            $table->float('late_fee')->nullable();

            $table->string('role')->nullable();
            $table->integer('role_id')->nullable();

            $table->integer('status')->nullable();
            $table->text('data')->nullable();

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
        Schema::dropIfExists('subscriptions');
    }
}

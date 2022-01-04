<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('nickname')->nullable();
            $table->float('amount')->default(0);
            $table->integer('interval_count');
            $table->string('interval')->nullable()->default(\App\Plan::DURATION_TYPE['MONTH']);
            $table->string('currency')->nullable();
            $table->string('billing_scheme')->nullable();
            $table->string('product')->nullable();
            $table->string('metadata')->nullable();
            $table->string('tiers')->nullable();
            $table->string('tiers_mode')->nullable();
            $table->string('transform_usage')->nullable();
            $table->string('trial_period_days')->nullable();
            $table->string('ref_id')->nullable();

            $table->integer('status')->default(\App\base\IStatus::ACTIVE);

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
        Schema::dropIfExists('plans');
    }
}

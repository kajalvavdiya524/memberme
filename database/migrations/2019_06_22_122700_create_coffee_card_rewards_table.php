<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoffeeCardRewardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coffee_card_rewards', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('coffee_card_id');
            $table->foreign('coffee_card_id')->references('id')->on('coffee_cards')->onDelete('no action');
            $table->string('name')->nullable();
            $table->string('message')->nullable();
            $table->string('image')->nullable();
            $table->integer('expiry')->nullable();
            $table->string('expiry_mode')->nullable();
            $table->integer('expiry_period_quantity')->nullable();
            $table->string('expiry_period_duration')->nullable();
            $table->dateTime('expiry_date')->nullable();
            $table->string('reward_code')->nullable();
            $table->string('qr_code')->nullable();
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
        Schema::dropIfExists('coffee_card_rewards');
    }
}

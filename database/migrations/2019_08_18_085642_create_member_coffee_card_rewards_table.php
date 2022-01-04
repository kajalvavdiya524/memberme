<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberCoffeeCardRewardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_coffee_card_rewards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('member_id')->nullable();
            $table->foreign('member_id')->references('id')->on('members')->onDelete('no action');

            $table->unsignedInteger('coffee_card_reward_id')->nullable();
            $table->foreign('coffee_card_reward_id')->references('id')->on('coffee_card_rewards')->onDelete('no action');

            $table->dateTime('reward_entry_date')->nullable();
            $table->dateTime('reward_expiry_date')->nullable();
            $table->dateTime('redeem_date_time')->nullable();
            $table->string('code')->nullable();
            $table->string('qr_code')->nullable();

            $table->smallInteger('status')->default(\App\base\IStatus::ACTIVE)->nullable();
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
        Schema::dropIfExists('member_coffee_card_rewards');
    }
}

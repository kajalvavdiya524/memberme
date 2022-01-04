<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMemberCoffeeCardForiegnKeyToMemberCoffeeCardRewards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_coffee_card_rewards', function (Blueprint $table) {
            $table->unsignedInteger('member_coffee_card_id')->nullable();
            $table->foreign('member_coffee_card_id','memberCoffeeCardToReward')->references('id')->on('member_coffee_cards')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_coffee_card_rewards', function (Blueprint $table) {
            $table->dropForeign('memberCoffeeCardToReward');
            $table->dropColumn('member_coffee_card_id');
        });
    }
}
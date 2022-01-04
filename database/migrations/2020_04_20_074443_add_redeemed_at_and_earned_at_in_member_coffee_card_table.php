<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRedeemedAtAndEarnedAtInMemberCoffeeCardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_coffee_card_rewards', function(Blueprint $table){
            $table->unsignedInteger('earned_at')->nullable();
            $table->foreign('earned_at','MemberCoffeeCardEarnedAtForignKey')->references('id')->on('organizations')->onDelete('no action');
            $table->unsignedInteger('redeemed_at')->nullable();
            $table->foreign('redeemed_at','MemberCoffeeCardRedeemedAtForignKey')->references('id')->on('organizations')->onDelete('no action');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_coffee_card_rewards', function(Blueprint $table){
            $table->dropForeign('MemberCoffeeCardEarnedAtForignKey');
            $table->dropForeign('MemberCoffeeCardRedeemedAtForignKey');
            $table->dropColumn(['earned_at', 'redeemed_at']);
        });
    }
}

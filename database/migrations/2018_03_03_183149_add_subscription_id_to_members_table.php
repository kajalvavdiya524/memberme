<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubscriptionIdToMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('members',function (Blueprint $table){
            $table->unsignedInteger('subscription_id')->nullable();
            $table->foreign('subscription_id','subscription_member_forignKey_members_table')->references('id')->on('subscriptions')->onDelete('set null');
            $table->dateTime('renewal')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('members',function (Blueprint $table){
            $table->dropForeign('subscription_member_forignKey_members_table');
            $table->dropColumn('subscription_id');
            $table->dropColumn('renewal');
        });
    }
}

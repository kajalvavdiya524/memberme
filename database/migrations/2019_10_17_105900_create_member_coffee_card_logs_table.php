<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberCoffeeCardLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_coffee_card_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('stamp_added');

            $table->unsignedInteger('member_id')->nullable();
            $table->foreign('member_id')->references('id')->on('members')->onDelete('no action');

            $table->unsignedInteger('member_coffee_card_id')->nullable();
            $table->foreign('member_coffee_card_id')->references('id')->on('member_coffee_cards')->onDelete('no action');


            $table->unsignedInteger('organization_id')->nullable();
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('no action');

            $table->timestamp('stamp_added_time');
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
        Schema::dropIfExists('member_coffee_card_logs');
    }
}

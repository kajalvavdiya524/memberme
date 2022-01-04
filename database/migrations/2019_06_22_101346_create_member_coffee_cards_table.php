<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberCoffeeCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_coffee_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('coffee_card_id');
            $table->foreign('coffee_card_id')->references('id')->on('coffee_cards')->onDelete('no action');


            $table->unsignedInteger('member_id');
            $table->foreign('member_id')->references('id')->on('members')->onDelete('no action');

            $table->string('code')->nullable();
            $table->string('qr_code')->nullable();
            $table->string('coffee_card_image')->nullable();
            $table->integer('stamp_balance')->nullable();
            $table->integer('stamp_earned')->nullable();
            $table->integer('stamp_required')->nullable();
            $table->integer('status')->nullable()->default(\App\base\IStatus::ACTIVE);

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
        Schema::dropIfExists('member_coffee_cards');
    }
}

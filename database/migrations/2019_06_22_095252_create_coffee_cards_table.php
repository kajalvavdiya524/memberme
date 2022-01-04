<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoffeeCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coffee_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('card_name')->nullable();
            $table->string('background')->nullable();
            $table->integer('number_of_stamps')->nullable();
            $table->text('position')->nullable();
            $table->text('style')->nullable();
            $table->string('card_code')->nullable();
            $table->string('qr_code')->nullable();
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
        Schema::dropIfExists('coffee_cards');
    }
}

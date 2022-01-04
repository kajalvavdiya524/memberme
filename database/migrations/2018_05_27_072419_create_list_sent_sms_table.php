<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListSentSmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_sent_sms', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('organization_id');
            $table->unsignedInteger('sms_list_id')->nullable();
            $table->text('message')->nullable();
            $table->integer('recipients')->nullable();
            $table->float('cost',4,4)->nullable();
            $table->integer('sms')->nullable();
            $table->dateTime('sent_date_time')->nullable();
            $table->integer('delivered')->nullable();
            $table->integer('pending')->nullable();
            $table->integer('bounced')->nullable();
            $table->integer('responses')->nullable();
            $table->integer('optouts')->nullable();
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
        Schema::dropIfExists('list_sent_sms');
    }
}

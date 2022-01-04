<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberOthersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_others', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('member_id');
            $table->integer('receive_email')->nullable()->default(\App\base\IStatus::ACTIVE);
            $table->integer('receive_sms')->nullable()->default(\App\base\IStatus::ACTIVE);
            $table->integer('newsletter')->nullable()->default(\App\base\IStatus::ACTIVE);
            $table->integer('mailing_list')->nullable()->default(\App\base\IStatus::ACTIVE);
            $table->integer('earn_points')->nullable()->default(\App\base\IStatus::ACTIVE);
            $table->integer('approved')->nullable()->default(\App\base\IStatus::INACTIVE);
            $table->integer('rsa')->nullable()->default(\App\base\IStatus::INACTIVE);
            $table->integer('deceased')->nullable()->default(\App\base\IStatus::INACTIVE);
            $table->integer('price_level')->nullable()->default(\App\base\IStatus::ACTIVE);
            $table->integer('senior')->nullable()->default(\App\base\IStatus::INACTIVE);
            $table->integer('points')->nullable();

            $table->string('swipe_card')->nullable();
            $table->string('prox_card')->nullable();
            $table->string('swipe_card_prefix')->nullable();
            $table->string('swipe_card_suffix')->nullable();
            $table->string('prox_card_prefix')->nullable();
            $table->string('prox_card_suffix')->nullable();

            $table->integer('credit_limit')->nullable();
            $table->float('discount')->nullable();
            $table->string('rsa_type')->nullable();
            $table->string('company')->nullable();
            $table->string('transferred_from')->nullable();
            $table->string('occupation')->nullable();
            $table->string('parent_code')->nullable();

            $table->integer('proposer_member_id')->nullable();
            $table->integer('secondary_member_id')->nullable();

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
        Schema::dropIfExists('member_others');
    }
}

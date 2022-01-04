<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStartDateInSubscriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions',function(Blueprint $table){
            $table->string('start_date_term')->nullable();
            $table->string('renewal_date_term')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('subscriptions',function(Blueprint $table){
            $table->dropColumn('start_date_term','renewal_date_term');
        });
    }
}

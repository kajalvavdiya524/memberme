<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDueDurationFieldInSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions',function(Blueprint $blueprint){
           $blueprint->integer('due_duration')->default(30)->nullable();
           $blueprint->softDeletes();
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
            $table->dropColumn('due_duration');
            $table->dropSoftDeletes();
        });
    }
}

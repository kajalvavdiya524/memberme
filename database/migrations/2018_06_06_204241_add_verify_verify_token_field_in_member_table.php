<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVerifyVerifyTokenFieldInMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('members',function(Blueprint $table){
            $table->string('verify_token',60)->nullable();
            $table->smallInteger('verify')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('members',function(Blueprint $table){
            $table->dropColumn('verify_token','verify');
        });
    }
}

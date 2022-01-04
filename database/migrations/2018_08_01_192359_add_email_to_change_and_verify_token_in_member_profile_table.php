<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmailToChangeAndVerifyTokenInMemberProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_profiles',function (Blueprint $table){
            $table->string('verify_token')->nullable();
            $table->string('email_to_change')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('member_profiles',function (Blueprint $table){
            $table->dropColumn('verify_token','email_to_change');
        });
    }
}

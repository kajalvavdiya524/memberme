<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPasswordSentAndChangeDateTimeInMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('members',function (Blueprint $table){
            $table->dateTime('password_sent_date_time')->nullable();
            $table->dateTime('password_change_date_time')->nullable();
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
            $table->dropColumn('password_sent_date_time','password_change_date_time');
        });
    }
}

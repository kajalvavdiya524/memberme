<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNextOfKinAndNextOfKinNumberInMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('members',function (Blueprint $table){
            $table->string('next_of_kin')->nullable();
            $table->string('next_of_kin_contact_no')->nullable();
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
            $table->dropColumn('next_of_kin','next_of_kin_contact_no');
        });
    }
}

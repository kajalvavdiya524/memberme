<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVerifyLinkSentDateTimeMemberProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_profiles', function (Blueprint $table) {
            $table->dateTime('verify_link_sent_date_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_profiles', function (Blueprint $table) {
            $table->dropColumn('verify_link_sent_date_time');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeChangedFieldsFromVarcharToTextInMemberNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_notifications', function (Blueprint $table) {
            $table->longText('changed_fields')->nullable()->change();
            $table->dateTime('clicked_date_time')->nullable();
            $table->string('clicked_by_user_ids')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_notifications', function (Blueprint $table) {
            $table->dropColumn('clicked_date_time','clicked_by_user_ids');
        });
    }
}

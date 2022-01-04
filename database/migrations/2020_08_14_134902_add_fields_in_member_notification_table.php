<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsInMemberNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_notifications', function (Blueprint $table) {
            $table->dateTime('updated_date_time')->nullable();
            $table->unsignedInteger('updated_by_user_id')->nullable();
            $table->foreign('updated_by_user_id')->references('id')->on('users')->onDelete('no action');
            $table->dropColumn('seen_by_user_ids');
            $table->dropColumn('clicked_by_user_ids');
            $table->unsignedInteger('clicked_by_user_id')->nullable();
            $table->foreign('clicked_by_user_id')->references('id')->on('users')->onDelete('no action');
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
            $table->dropForeign('member_notifications_clicked_by_user_id_foreign');
            $table->dropForeign('member_notifications_updated_by_user_id_foreign');
            $table->dropColumn('updated_date_time','clicked_by_user_id','updated_by_user_id');
            $table->string('seen_by_user_ids')->nullable();
            $table->string('clicked_by_user_ids')->nullable();
        });
    }
}

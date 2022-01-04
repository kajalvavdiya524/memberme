<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsUpdatedInMemberNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_notifications', function (Blueprint $table) {
            $table->smallInteger('is_updated')->nullable()->default(\App\base\IStatus::INACTIVE);
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
            $table->dropColumn('is_updated');
        });
    }
}

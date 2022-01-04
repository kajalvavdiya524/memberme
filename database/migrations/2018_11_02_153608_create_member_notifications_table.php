<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('changed_fields')->nullable();
            $table->integer('added_by_id')->nullable();
            $table->string('added_by_type',20)->nullable();
            $table->dateTime('seen_date_time')->nullable();
            $table->integer('organization_id')->nullable();
            $table->text('seen_by_user_ids')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_notifications');
    }
}

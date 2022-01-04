<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddScheduleFieldsInEmailTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $table->integer('email_type')->nullable();
            $table->integer('event')->nullable();
            $table->integer('send_email_date')->nullable();
            $table->integer('before_or_after')->nullable();
            $table->integer('days')->nullable();
            $table->integer('send_email_time')->nullable();
            $table->integer('email_group')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $table->dropColumn('send_email_date', 'send_email_time', 'before_or_after','days','email_type','event','email_group');
        });
    }
}

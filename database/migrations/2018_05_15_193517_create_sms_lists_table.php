<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ref_id');
            $table->string('name')->nullable();
            $table->unsignedInteger('group_id')->nullable();
            $table->unsignedInteger('organization_id')->nullable();
            $table->longText('data')->nullable();
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
        Schema::dropIfExists('sms_lists');
    }
}

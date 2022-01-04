<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->smallInteger('current')->nullable();
            $table->integer('user_id')->nullable();
            $table->longText('data')->nullable();
            $table->integer('status');
            $table->timestamps();
        });

        $statement = "ALTER TABLE organizations AUTO_INCREMENT = 15550;";
        DB::unprepared($statement);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('organizations');
    }
}

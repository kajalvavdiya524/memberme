<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_user', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->index('user')->unsigned();
            //            $table->foreign('user_id')->references('id')->on('users')->onUpdate('no action')->onDelete('cascade');



            $table->integer('role_id')->index('role')->unsigned();
            //            $table->foreign('role_id')->references('id')->on('roles')->onUpdate('no action')->onDelete('cascade');

            $table->integer('current')->nullable();

            //relation wit the organization.
            $table->integer('organization_id')->nullable();

            //status with organization.
            $table->integer('status')->default(1);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_user');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberProfilesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('contact_no')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('validate_id')->nullable();
            $table->string('email')->nullable();
            $table->string('title')->nullable();
            $table->string('facebook_id')->nullable();
            $table->string('known_as')->nullable();
            $table->string('gender')->nullable();
            $table->string('phone')->nullable();
            $table->string('password')->nullable();
            $table->integer('member_id')->nullable();
            $table->integer('physical_address_id')->nullable();
            $table->integer('postal_address_id')->nullable();
            $table->string('identity')->nullable();
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
        Schema::dropIfExists('member_profiles');
    }
}

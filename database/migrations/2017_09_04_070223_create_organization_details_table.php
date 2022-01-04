<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_details', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('organization_id')->unsigned();
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade')->onUpdate('no action');

            $table->string('bio')->nullable();
            $table->string('contact_name');
            $table->string('contact_email');
            $table->string('contact_phone');
            $table->string('office_phone')->nullable();
            $table->integer('industry');
            $table->string('account_no');
            $table->string('logo')->nullable();
            $table->string('cover')->nullable();
            $table->string('physical_address_id')->nullable();
            $table->string('postal_address_id')->nullable();
            $table->string('gst_number')->nullable();
            $table->string('starting_member')->nullable();
            $table->string('starting_receipt')->nullable();
            $table->bigInteger('next_member')->nullable()->default(1);
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
        Schema::dropIfExists('organization_details');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationKioskTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_kiosk_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('organization_id');
            $table->unsignedInteger('kiosk_background_id');
            $table->integer('template_no')->nullable();
            $table->string('date_color')->nullable();
            $table->string('logo')->nullable();
            $table->string('label')->nullable();
            $table->string('text_one')->nullable();
            $table->string('text_two')->nullable();
            $table->longText('text_one_style')->nullable();
            $table->longText('text_two_style')->nullable();
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
        Schema::dropIfExists('organization_kiosk_templates');
    }
}

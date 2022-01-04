<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfficeUsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('office_uses', function (Blueprint $table){
            $table->increments('id');
            $table->integer('kiosk')->nullable()->default(\App\base\IStatus::INACTIVE);
            $table->unsignedInteger('organization_id');
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('no action');
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
        Schema::dropIfExists('office_uses');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('activity')->nullable()->default(\App\base\IStatus::ACTIVE);
            $table->integer('rsa')->nullable()->default(\App\base\IStatus::ACTIVE);
            $table->integer('group')->nullable()->default(\App\base\IStatus::ACTIVE);
            $table->integer('interest')->nullable()->default(\App\base\IStatus::ACTIVE);
            $table->integer('status')->nullable()->default(\App\base\IStatus::ACTIVE);
            $table->integer('organization_id')->nullable();
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
        Schema::dropIfExists('organization_options');
    }
}

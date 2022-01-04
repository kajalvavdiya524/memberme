<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationSmsSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_sms_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('organization_id');
            $table->string('url')->nullable();
            $table->string('account_id')->nullable();
            $table->string('api_key')->nullable();
            $table->string('api_secret')->nullable();
            $table->string("sms_username")->nullable();
            $table->string("sms_password")->nullable();
            $table->float('sms_rate')->nullable();
            $table->float('sms_balance')->nullable();
            $table->string('type')->nullable();
            $table->smallInteger('status')->default(\App\base\IStatus::ACTIVE);
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
        Schema::dropIfExists('organization_sms_settings');
    }
}

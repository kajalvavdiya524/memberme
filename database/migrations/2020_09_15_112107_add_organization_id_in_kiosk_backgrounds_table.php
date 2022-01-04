<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrganizationIdInKioskBackgroundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kiosk_backgrounds', function(Blueprint $table){
            $table->unsignedInteger('organization_id')->nullable();
            $table->foreign('organization_id','OrganizationKioskBackgroundForignKey')->references('id')->on('organizations')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kiosk_backgrounds', function(Blueprint $table){
            $table->dropForeign('OrganizationKioskBackgroundForignKey');
            $table->dropColumn('organization_id');
        });
    }
}

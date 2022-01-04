<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTemplateIdFieldInMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('members',function (Blueprint $table){
            $table->unsignedInteger('organization_card_template_id')->nullable();
            $table->foreign('organization_card_template_id','members_organization_card_template_id_foreign')->references('id')->on('organization_card_templates')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('members',function (Blueprint $table){
            $table->dropForeign('members_organization_card_template_id_foreign');
            $table->dropColumn('organization_card_template_id');
        });
    }
}

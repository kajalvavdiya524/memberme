<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShowImageInOrganizationTemplateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organization_card_templates', function (Blueprint $table) {
            $table->smallInteger('show_image')->nullable()->defauld(\App\base\IStatus::ACTIVE);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organization_card_templates', function (Blueprint $table) {
            $table->dropColumn('show_image');
        });
    }
}

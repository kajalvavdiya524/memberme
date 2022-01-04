<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStylesTagsForFrontAndBackImage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('voucher_parameters', function (Blueprint $table) {
            $table->text('front_image_style')->nullable();
            $table->text('back_image_style')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('voucher_parameters', function (Blueprint $table) {
            $table->dropColumn('front_image_style','back_image_style');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNumberAndNameFieldInAdvertisingImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('advertising_images', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->integer('sequence')->default(0);
            $table->string('sound_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('advertising_images', function (Blueprint $table) {
            $table->dropColumn('sequence','name','sound_name');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertisingImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertising_images', function (Blueprint $table) {
            $table->increments('id');
            $table->text('url')->nullable();
            $table->unsignedInteger('advertising_id')->index();
            $table->foreign('advertising_id')->references('id')->on('advertisings')->onDelete('cascade');
            $table->string('animation')->nullable();
            $table->string('sound')->nullable();
            $table->float('duration')->nullable();
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
        Schema::dropIfExists('advertising_images');
    }
}

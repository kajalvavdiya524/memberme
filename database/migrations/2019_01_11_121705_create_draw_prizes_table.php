<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDrawPrizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('draw_prizes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->dateTime('drawn_date_time')->nullable();

            $table->unsignedInteger('member_id')->index()->nullable();
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');

            $table->unsignedInteger('draw_id')->index();
            $table->foreign('draw_id')->references('id')->on('draws')->onDelete('no action');

            $table->unsignedInteger('organization_id')->index();
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');

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
        Schema::dropIfExists('draw_prizes');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDrawEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('draw_entries', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('draw_id')->index();
            $table->foreign('draw_id')->references('id')->on('draws')->onDelete('cascade');

            $table->unsignedInteger('organization_id')->index();
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('no action');

            $table->unsignedInteger('member_id')->index();
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');

            $table->dateTime('entry_date_time')->nullable();

            $table->integer('status')->default(\App\base\IStatus::ACTIVE);

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
        Schema::dropIfExists('draw_entries');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDrawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('draws', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('organization_id')->index();
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');

            $table->string('name')->nullable();
            $table->integer('frequency')->nullable();
            $table->dateTime('duration_start')->nullable();
            $table->dateTime('duration_finish')->nullable();
            $table->integer('frequency_limit')->nullable()->default(\App\base\IStatus::ACTIVE);
            $table->integer('frequency_limit_quantity')->nullable();
            $table->integer('frequency_limit_quantity_period')->nullable();
            $table->integer('entry_limit')->nullable()->default(\App\Draw::ENTRY_LIMIT['NO']);
            $table->integer('entry_limit_quantity')->nullable();
            $table->integer('print_entry')->nullable();
            $table->string('status')->default(\App\base\IStatus::ACTIVE)->nullable();
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
        Schema::dropIfExists('draws');
    }
}

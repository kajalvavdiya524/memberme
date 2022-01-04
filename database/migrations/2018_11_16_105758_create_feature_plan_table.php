<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeaturePlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feature_plan', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('feature_id');
            $table->foreign('feature_id', 'feature_id_foreign_key_in_feature_plan_table')->references('id')->on('features');
            $table->unsignedInteger('plan_id');
            $table->foreign('plan_id', 'plan_id_foreign_key_in_feature_plan_table')->references('id')->on('plans');
            $table->float('amount')->nullable();
            $table->string('limit')->nullable();
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
        Schema::dropIfExists('feature_plan');
    }
}

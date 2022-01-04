<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeatureUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feature_user', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('feature_id');
            $table->foreign('feature_id', 'feature_id_foreign_key_in_feature_user_table')->references('id')->on('features')->onDelete('no action');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id', 'user_id_foreign_key_in_feature_user_table')->references('id')->on('users')->onDelete('no action');
            $table->unsignedInteger('organization_id');
            $table->foreign('organization_id', 'organization_id_foreign_key_in_feature_user_table')->references('id')->on('organizations')->onDelete('no action');
            $table->date('expiry')->nullable();
            $table->integer('limit')->nullable();
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
        Schema::dropIfExists('feature_user');
    }
}

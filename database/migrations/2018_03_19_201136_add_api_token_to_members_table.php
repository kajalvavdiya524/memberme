<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApiTokenToMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('members',function (Blueprint $table){
            $table->string('api_token',60)->nullable()->unique();
            $table->unique(['email','organization_id'],'store_customers_store_customer_unique');
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
            $table->dropColumn('api_token');
            $table->dropUnique('store_customers_store_customer_unique');
        });
    }
}

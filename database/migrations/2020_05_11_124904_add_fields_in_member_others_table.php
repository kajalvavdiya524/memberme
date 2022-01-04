<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsInMemberOthersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_others', function (Blueprint $table) {
            $table->integer('physical_card')->nullable()->default(\App\base\IStatus::ACTIVE);
            $table->integer('print_card')->nullable()->default(\App\base\IStatus::ACTIVE);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_others', function (Blueprint $table) {
            $table->dropColumn('physical_card','physical_card');
        });
    }
}

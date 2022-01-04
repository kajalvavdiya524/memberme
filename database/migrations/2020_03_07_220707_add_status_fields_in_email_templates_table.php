<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusFieldsInEmailTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_templates', function(Blueprint $table){
            $table->smallInteger('status')->default(\App\base\IStatus::ACTIVE)->nullable();
            $table->boolean('is_valid')->default(true)->nullable();
            $table->string('invalid_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('email_templates', function(Blueprint $table){
            $table->dropColumn('status', 'is_valid', 'invalid_reason');
        });
    }
}

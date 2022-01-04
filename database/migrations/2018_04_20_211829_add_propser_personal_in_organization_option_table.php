 <?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPropserPersonalInOrganizationOptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organization_options',function(Blueprint $table){
            $table->smallInteger('proposer')->default(\App\base\IStatus::ACTIVE)->nullable();
            $table->smallInteger('personal')->default(\App\base\IStatus::ACTIVE)->nullable();
            $table->smallInteger('genealogy')->default(\App\base\IStatus::INACTIVE)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organization_options',function(Blueprint $table){
            $table->dropColumn('proposer','personal','genealogy');
        });
    }
}

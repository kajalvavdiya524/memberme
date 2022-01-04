<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAutoAssignInSubscriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions',function (Blueprint $table){
            $table->smallInteger('auto_assign')->nullable();
            $table->smallInteger('payment_reminder')->nullable()->default(\App\base\IStatus::INACTIVE);
            $table->string('payment_reminder_term')->nullable();
            $table->smallInteger('send_invoice')->nullable()->default(\App\base\IStatus::INACTIVE);
            $table->string('send_invoice_date')->nullable();
            $table->string('pro_rata_date')->nullable();
            $table->integer('overdue_days')->nullable();
            $table->float('overdue_fee')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriptions',function(Blueprint $table){
            $table->dropColumn('auto_assign','payment_reminder', 'payment_reminder_term', 'send_invoice', 'send_invoice_date','pro_rata_date','overdue_fee','overdue_days');
        });
    }
}

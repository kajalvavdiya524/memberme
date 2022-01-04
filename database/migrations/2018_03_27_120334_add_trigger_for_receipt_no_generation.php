<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTriggerForReceiptNoGeneration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
                CREATE TRIGGER receipt_no_generation_trigger BEFORE INSERT ON receipts
        FOR EACH ROW BEGIN
            SET NEW.`receipt_no` = (SELECT IFNULL(MAX(receipt_no), 10000) + 1 FROM receipts WHERE organization_id = NEW.organization_id);
            update organization_details set starting_receipt = NEW.receipt_no + 1 where organization_id = NEW.organization_id;
        END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `receipt_no_generation_trigger`');
    }
}

<?php

use App\Record;
use Illuminate\Database\Seeder;

class RecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $record = new Record();
        $record->name = 'Automotive';
        $record->record_type_id = \App\base\IRecordType::INDUSTRY;
        $record->data = null;
        $record->parent_id =null;
        $record->save();

        $record = new Record();
        $record->name = 'Club';
        $record->record_type_id = \App\base\IRecordType::INDUSTRY;
        $record->data = null;
        $record->parent_id =null;
        $record->save();

        $record = new Record();
        $record->name = 'Construction';
        $record->record_type_id = \App\base\IRecordType::INDUSTRY;
        $record->data = null;
        $record->parent_id =null;
        $record->save();

        $record = new Record();
        $record->name = 'Education';
        $record->record_type_id = \App\base\IRecordType::INDUSTRY;
        $record->data = null;
        $record->parent_id =null;
        $record->save();

        $record = new Record();
        $record->name = 'Entertainment';
        $record->record_type_id = \App\base\IRecordType::INDUSTRY;
        $record->data = null;
        $record->parent_id =null;
        $record->save();

        $record = new Record();
        $record->name = 'Manufacturing';
        $record->record_type_id = \App\base\IRecordType::INDUSTRY;
        $record->data = null;
        $record->parent_id =null;
        $record->save();

        $record = new Record();
        $record->name = 'Professional Services';
        $record->record_type_id = \App\base\IRecordType::INDUSTRY;
        $record->data = null;
        $record->parent_id =null;
        $record->save();

        $record = new Record();
        $record->name = 'Retail';
        $record->record_type_id = \App\base\IRecordType::INDUSTRY;
        $record->data = null;
        $record->parent_id =null;
        $record->save();

        $record = new Record();
        $record->name = 'Trade Services';
        $record->record_type_id = \App\base\IRecordType::INDUSTRY;
        $record->data = null;
        $record->parent_id =null;
        $record->save();

        $record = new Record();
        $record->name = 'Travel';
        $record->record_type_id = \App\base\IRecordType::INDUSTRY;
        $record->data = null;
        $record->parent_id =null;
        $record->save();

    }
}

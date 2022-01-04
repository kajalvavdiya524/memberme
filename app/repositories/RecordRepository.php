<?php
/**
 * Created by PhpStorm.
 * User: Faisal
 * Date: 10/25/2017
 * Time: 7:43 AM
 */

namespace App\repositories;


use App\base\IRecordType;
use App\base\IStatus;
use App\Record;

class RecordRepository
{
    /* @var $record Record*/
    public  $record;

    /**
     * RecordRepository constructor.
     * @param Record $record
     */
    public function __construct(Record $record)
    {
        $this->record = $record;
    }

    public function create($data)
    {
        $record = new Record();
        $record->name = $data['name'];
        $record->record_type_id = $data['record_type_id'];
        $record->data =($data)?json_encode($data):null;
        $record->parent_id = (isset($data['parent_id']))?$data['parent_id']:null;
        $record->save();
        return $record;
    }

    public function update($data)
    {
        $record = $this->record->find($data['id']);
        if($record){
            $record->name = $data['name'];
            $record->record_type_id = $data['record_type_id'];
            $record->data =($data)?json_encode($data):null;
            $record->parent_id = (isset($data['parent_id']))?$data['parent_id']:null;
            $record->update();
        }
        return $record;
    }

    /**
     * Return the list of Industries
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAllIndustries(){

        return $this->record->where([
            'record_type_id' => IRecordType::INDUSTRY,
            'status' => IStatus::ACTIVE,
        ])->get([
            'name',
            'id',
            'created_at',
            'status',
        ]);
    }

    /**
     * Return the list of members
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAllPosMembers(){

        return $this->record->where([
            'record_type_id' => IRecordType::POS_MEMBERS,
            'status' => IStatus::ACTIVE,
        ])->get();
    }

    /**
     * @param $orgId
     * @return Record[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAllPosClientCardName($orgId)
    {
        return $this->record->where([
            'record_type_id' => IRecordType::POS_CLIENT_CARD_NAME,
            'status' => IStatus::ACTIVE,
            'organization_id' => $orgId
        ])->get();
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Faisal
 * Date: 2/13/2018
 * Time: 12:09 AM
 */

namespace App\repositories;


use App\base\IStatus;
use App\Group;
use App\Member;
use App\Organization;

class GroupRepository
{
    /**
     * @param $memberId
     * @param $groupId
     * @param $type
     * @return mixed|null
     */
    public function find($memberId = null, $groupId, $type)
    {
        $member = Member::find($memberId);
        if (!empty($member)) {
            $group = $member->groups()->where(
                [
                    'groups.id' =>  $groupId,
                    'type' => $type
                ]
            )->first();
            if(empty($group)){
                return null;
            }else{
                return $group;
            }
        }else{
            $group = Group::where(
                [
                    'id' =>  $groupId,
                    'type' => $type
                ]
            )->first();
            if(empty($group)){
                return null;
            }else{
                return $group;
            }
        }
    }

    /**
     * @param Organization $organization
     * @param $name
     * @param $type
     * @return Group|\Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function findOrCreate(Organization $organization, $name, $type)
    {
        $group = $organization->groups()->where(['name' => $name])->first();
        if(empty($group)){
            $group = new Group();
            $group->name = $name;
            $group->organization_id = $organization->id;
            $group->type = $type;
            $group->status = IStatus::ACTIVE;
            $group->save();
        }
        return $group;
    }
}
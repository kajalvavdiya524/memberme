<?php

namespace App\Http\Controllers;

use App\base\IRecordType;
use App\base\IResponseCode;
use App\base\IStatus;
use App\Group;
use App\Helpers\DropdownHelper;
use App\Http\Requests\CreateGroupRequest;
use App\Organization;
use App\repositories\GroupRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    /* @var $groupRepo GroupRepository*/
    public $groupRepo;

    public function __construct()
    {
        $this->groupRepo = new GroupRepository();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'name' => 'required',
            'type' => 'required|in:'.Group::TYPE['ADJUNCT'].','.Group::TYPE['ACTIVITY'].','.Group::TYPE['INTEREST'].','.Group::TYPE['SKILL'],
        ];
        $validator = Validator($request->all(),$validationRules);
        if(!$validator->fails()){
            /* @var $organization Organization*/
            $organization = $request->organization;
            $group = new Group();
            $group->name = $request->name;
            $group->status = IStatus::ACTIVE;
            $group->type = $request->type;
            $organization->groups()->save($group);

            $message = null;
            if($group->type == Group::TYPE['ADJUNCT']){
                $message = 'Group created successfully';
            }else if($group->type == Group::TYPE['INTEREST']) {
                $message = 'Interest created successfully';
            }else if($group->type == Group::TYPE['ACTIVITY']){
                $message = 'Activity created successfully';
            }else if($group->type == Group::TYPE['SKILL']){
                $message = 'Skill created successfully';
            }
            return api_response($group,null, $message);
        }else{
            return api_response(null,$validator->errors(),IResponseCode::INVALID_PARAMS);
        }
    }

    public function getList(Request $request)
    {
        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'type' => 'required|in:'.Group::TYPE['ADJUNCT'].','.Group::TYPE['ACTIVITY'].','.Group::TYPE['INTEREST'].','.Group::TYPE['SKILL'],
            'name' => 'string',
        ];
        $validator = Validator($request->all(),$validationRules);
        if(!$validator->fails()) {
            /* @var $organization Organization */
            $organization = $request->organization;
            $query = $organization->groups()->where([
                'status' => IStatus::ACTIVE,
                'type' => $request->type,
            ])->whereNull('member_id');
            if(!empty($request->get('name'))){
                $query->where('name','like', '%'.$request->get('name').'%');
            }

            $groups = $query->orderBy('groups.name','asc')->get(['name','id']);

            return api_response($groups);
        }else{
            return api_response(null,$validator->errors(),IResponseCode::INVALID_PARAMS);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'name' => 'required',
            'type' => 'required|in:'.Group::TYPE['ADJUNCT'].','.Group::TYPE['ACTIVITY'].','.Group::TYPE['INTEREST'].','.Group::TYPE['SKILL'],
            'group_id' => 'required|exists:groups,id',
            'member_id' => 'exists:members,id',
        ];

        $validator = Validator($request->all(), $validationRules);

        if (!$validator->fails()) {
            if($request->member_id && !empty($request->member_id )){
                $group = $this->groupRepo->find($request->member_id,$request->group_id,$request->type);
            }else{
                $group = $this->groupRepo->find(null,$request->group_id,$request->type);
            }

            $validator->after(function ($validator) use ($group){
                if(empty($group)){
                    $validator->getMessageBag()->add('group_id','Group not found');
                }
            });

            if (!$validator->fails()) {

                $group->name = $request->name;
                $group->save();

                $message = null;
                if($group->type == Group::TYPE['ADJUNCT']){
                    $message = 'Group updated successfully';
                }else if($group->type == Group::TYPE['INTEREST']) {
                    $message = 'Interest Updated Successfully';
                }else if($group->type == Group::TYPE['ACTIVITY']){
                    $message = 'Activity Updated Successfully';
                }else if($group->type == Group::TYPE['SKILL']){
                    $message = 'Skill Updated Successfully';
                }

                return api_response($group,null,$message);
            } else {
                return api_response(null, $validator->errors(), IResponseCode::INVALID_PARAMS);
            }
        } else {
            return api_response(null, $validator->errors(), IResponseCode::INVALID_PARAMS);
        }
    }

    public function delete(Request $request)
    {

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'type' => 'required|in:'.Group::TYPE['ADJUNCT'].','.Group::TYPE['ACTIVITY'].','.Group::TYPE['INTEREST'].','.Group::TYPE['SKILL'],
            'group_id' => 'required|exists:groups,id'
        ];

        $validator = Validator($request->all(), $validationRules);

        if (!$validator->fails()) {
            $group = $this->groupRepo->find(null,$request->group_id,$request->type);

            $validator->after(function ($validator) use ($group){
                if(empty($group)){
                    $validator->getMessageBag()->add('group_id','Group not found');
                }
            });

            if (!$validator->fails()) {
                $group->members()->detach();    //detaching members from that group.
                $group->delete();   // deleting member.
                return api_response(null, null, 'Group deleted Scuccefully');

            } else {
                return api_response(null, $validator->errors(), null,IResponseCode::INVALID_PARAMS);
            }

        } else {
            return api_response(null, $validator->errors(),null, IResponseCode::INVALID_PARAMS);
        }
    }

    public function getAllGroups(Request $request)
    {
        /** @var $organization Organization */
        $organization =  $request->get(Organization::NAME);

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id'
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        $groups = $organization->groups()
//            ->whereHas('members',function($query){
//            $query->whereNotNull('contact_no');
//            })
            ->where('type' , '!=' , Group::TYPE['SKILL'])
            ->get();

        return api_response($groups);
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Support\Collection
     */
    public function dropdownList(Request $request)
    {
        $organization = $request->get(Organization::NAME);
        $addStatusGroups = IStatus::ACTIVE;
        if(!empty($request->get('should_remove_status_groups'))){
            $shouldRemoveStatusGroups = $request->get('should_remove_status_groups');
        }
        $data = DropdownHelper::getGroupList($organization,$shouldRemoveStatusGroups);
        return api_response($data);
    }
}

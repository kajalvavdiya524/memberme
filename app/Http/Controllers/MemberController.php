<?php

namespace App\Http\Controllers;

use App\base\AddressType;
use App\base\IResponseCode;
use App\base\IStatus;
use App\ChangeLog;
use App\CoffeeCard;
use App\Country;
use App\Events\MemberProfileChange;
use App\Exceptions\ApiException;
use App\Group;
use App\Helpers\ApiHelper;
use App\KioskVoucherParameter;
use App\Member;
use App\MemberCoffeeCard;
use App\MemberDirectPaymentDetails;
use App\MemberNotification;
use App\MemberOther;
use App\MemberProfile;
use App\Organization;
use App\OrganizationCardTemplate;
use App\Payment;
use App\Record;
use App\repositories\CoffeeCardRepository;
use App\repositories\KioskRepository;
use App\repositories\MemberRepository;
use App\repositories\OrganizationRepository;
use App\repositories\RecordRepository;
use App\repositories\VoucherRepository;
use App\Services\Untill\UntillService;
use App\SmsList;
use App\Subscription;
use App\User;
use App\Voucher;
use App\VoucherParameter;
use Carbon\Carbon;
use Config;
use DataTables;
use DateTime;
use DB;
use File;
use Hamcrest\Core\IsSame;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\True_;
use Storage;

class MemberController extends Controller
{
    /* @var $memberService MemberRepository */
    public $memberService;

    public function __construct(MemberRepository $memberRepository)
    {
        $this->memberService = $memberRepository;
    }

    /**
     * Adds member to the db and performs the necessary steps.
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws ApiException
     */
    public function addMember(Request $request)
    {
        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'email' => 'email',
        ];

        $validator = Validator($request->all(), $validationRules);

        if (!$validator->fails()) {
            $validator->after(function ($validator) use ($request) {
                if (!empty($request->email)) {
                    $alreadyExistingMember = Member::where([
                        'organization_id' => $request->get('organization_id'),
                        'email' => $request->get('email')
                    ])->first();
                    if (!empty($alreadyExistingMember)) {
                        $validator->getMessageBag()->add('email', 'Member with same email already exist');
                    }
                }
            });

            if ($validator->fails()) {
                return api_error($validator->errors());
            }

            /** @var $organization Organization */
            $organization = $request->get(Organization::NAME);


            //region Check if the member id already exists.
            if (!empty($request->get('member_id')))
                $alreadyExistingMemberId = $organization->members()
                    ->where('member_id', $request->get('member_id'))->first();
            if (!empty($alreadyExistingMemberId)) {
                return api_error(['error' => 'Member with same ID already exist']);
            }
            //endregion
            $newMember = $this->memberService->addMember($request->all(), false);

            if ($newMember == false) {
                return api_error(['email' =>
                    'Internal server error in adding member.']);
            }

            $newMember['next_member_no'] = !empty($newMember->organization->details->next_member) ? $newMember->organization->details->next_member : null;     //To show the next member number in popup frontend.
            $result = ApiHelper::apiResponse($newMember, null, 'Member successfully added to your organization');
            return response($result, IResponseCode::SUCCESSFULLY_CREATED);

        } else {
            return response(ApiHelper::apiResponse(null, $validator->errors()), IResponseCode::INVALID_PARAMS);
        }
    }

    public function changeField(Request $request)
    {
        //region Base Validation Rules
        $validationRules = [
            'id' => 'required|exists:members,id',
            'organization_id' => 'required|exists:organizations,id',
        ];
        //endregion

        $fields = $request->input();

        //region If Fields is more then 1 return back
        if (count($fields) > 3) {
            return response(ApiHelper::apiResponse(null, ['error' => 'You can not edit more then one field']), IResponseCode::PRECONDITION_FAILED);
        }
        //endregion

        $validator = Validator($request->all(), $validationRules);

        //region Validating and updating the fields
        $memberFeild = null;
        $memberFieldValue = null;
        $validKey = false;

        if (!$validator->fails()) {

            $validator->after(function ($validator) use ($request) {
                if ($request->filled('email')) {
                    $alreadyExistingMember = Member::where([
                        'organization_id' => $request->get('organization_id'),
                        'email' => $request->get('email')
                    ])->first();
                    if (!empty($alreadyExistingMember)) {
                        $validator->getMessageBag()->add('email', 'Member with same email already exist');
                    }
                }
            });

            if ($validator->fails()) {
                return api_error($validator->errors());
            }
            //region Validating Field Name
            foreach ($fields as $key => $value) {
                if ($key != 'organization_id' && $key != 'id') {
                    if (in_array($key, MemberRepository::AuthorizedFields())) {
                        $memberFeild = $key;
                        $memberFieldValue = $value;
                        $validKey = true;
                    } else {
                        $validator->getMessageBag()->add($key, 'Invalid Field Name');
                        return response(ApiHelper::apiResponse(null, $validator->errors()), IResponseCode::INVALID_PARAMS);
                    }
                }
            }
            //endregion

            if ($validKey) {
                $member = Member::where(['id' => $request->id, 'organization_id' => $request->organization_id])->first();
                if ($member) {
                    if ($memberFeild == 'date_of_birth') {
                        $dateObj = DateTime::createFromFormat('d/m/Y', $memberFieldValue);
                        $date = date('Y-m-d', $dateObj->getTimestamp());
                        $memberFieldValue = $date;
                    }
                    $member = $this->memberService->updateField($member, $memberFeild, $memberFieldValue);
                    return api_response($member, null, 'Member ' . $key . ' has successfully updated');
                } else {
                    $validator->getMessageBag()->add('organization_id', 'This member is not assosiated with this Organization.');
                    return response(ApiHelper::apiResponse(null, $validator->errors()), IResponseCode::INVALID_PARAMS);
                }
            }
        } else {
            return response(ApiHelper::apiResponse(null, $validator->errors()), IResponseCode::INVALID_PARAMS);
        }
        //endregion
    }
    /**
     * @param Request $reques
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function getMemberDetails(Request $request)
    {

        $validationRules = [
            'id' => 'required|exists:members,id',
            'organization_id' => 'required|exists:organizations,id',
        ];

        $validator = Validator($request->all(), $validationRules);

        if (!$validator->fails()) {
            $member = Member::where([
                'id' => $request->id,
                'organization_id' => $request->organization_id,
            ])->with('physicalAddress', 'postalAddress', 'groups', 'others', 'template')->first();
            $validator->after(function ($validator) use ($member) {
                if (!$member) {
                    $validator->getMessageBag()->add('id', 'Member not found');
                }
            });
            if (!$validator->fails()) {
                $result = ApiHelper::apiResponse($member, null, 'Member Retrieved Successfully');
                return response($result, IResponseCode::SUCCESS);
            } else {
                return response(ApiHelper::apiResponse(null, $validator->errors()), IResponseCode::INVALID_PARAMS);
            }

        } else {
            return response(ApiHelper::apiResponse(null, $validator->errors()), IResponseCode::INVALID_PARAMS);
        }
    }

    public function updateAddress(Request $request, $type)
    {
        $addressType = null;
        $validationRules = [];

        //region Setting Address Type & ValidationRules
        if ($type == 'physical') {
            $addressType = AddressType::PHYSICAL_ADDRESS;
            //region Setting  ValidationRules Against Physical Address
            $validationRules = [
                'member_id' => 'required|exists:members,id',
                'country' => 'required|numeric',
                'first_address' => 'required',
                'suburb' => 'required',
                'city' => 'required',
                //  'physical_region' => 'required',
                'postal_code' => 'required',
            ];
            //endregion
        } else if ($type == 'postal') {

            $validationRules = [
                'member_id' => 'required|exists:members,id',
                'country' => 'required|numeric',
            ];

            $addressType = AddressType::POSTAL_ADDRESS;
        }
        //endregion

        //region If address type is unrecognizable returning back
        if ($addressType == null) {
            return ApiHelper::apiResponse(null, ['type' => 'Invalid Address Type'], IResponseCode::INVALID_PARAMS);
        }
        //endregion

        $validator = Validator($request->all(), $validationRules);

        //region If validator fails return back with errors.
        if ($validator->fails()) {    // if validator fails then return errors as response.
            return ApiHelper::apiResponse(null, $validator->errors(), IResponseCode::INVALID_PARAMS);
        }
        //endregion

        $member = $this->memberService->addAddress($request->all(), $addressType);

        $result = ApiHelper::apiResponse($member, null, $type . ' address saved');
        return response($result, IResponseCode::SUCCESS);
    }

    public function getList(Request $request, $orgId = null)
    {
        set_time_limit(300);

        $validationRules = [
//            'organization_id' => 'required|exists:organizations,id'
        ];
        $validator = Validator($request->all(), $validationRules);
        if ($validator->fails()) {
            return api_response(null, $validator->errors(), null, IResponseCode::INVALID_PARAMS);
        }
        /* @var $organization Organization */
        if (!empty($orgId)) {
            $organization = Organization::find($orgId);
        } else {
            $organization = Organization::find($request['organization_id']);
        }

        //region Organization not found response
        if (empty($organization)) {
            return api_error(['error' => 'Organization not found']);
        }
        //endregion

        $resultSet = $organization->members()->with([

            'organization' => function ($query) {
                $query->select('id', 'name');
                $query->with(['smsSetting' => function ($q) {
                    $q->select('id');
                }]);
            },
            'physicalAddress', 'postalAddress', 'groups' => function ($query) {
//                $query->orderBy('groups.name','asc');
            }, 'others', 'template',
            'notes' => function ($query) {
                $query->select('id', 'title', 'description', 'user_id', 'created_at', 'updated_at', 'member_id');
                $query->with(['user' => function ($q) {
                    $q->select('id', 'first_name', 'last_name');
                }]);
            }

        ]);

        if (!empty($request->name)) {
            $resultSet = $resultSet->where(function ($query) use ($request) {
                $query->where('first_name', 'like', '%' . $request->name . '%')->orWhere('last_name', 'like', '%' . $request->name . '%');
                $query->orWhere('member_id', 'like', '%' . $request->name . '%');
            });
        }

        if (!empty($request->get('order'))) {
            $order = $request->get('order');
            $shouldSortByMemberId = false;
            if (is_array($order)) {
                foreach ($order as $item) {
                    if (array_get($item, 'column') == 0) {
                        $shouldSortByMemberId = true;
                        $sortByOrder = array_get($item, 'dir');
                    }
                }
            }

            if ($shouldSortByMemberId) {
                $resultSet = $resultSet->addSelect(DB::raw('* , member_id * 1 as member_idINT'));
                $resultSet->orderBy('member_idINT', $sortByOrder);
            }
        }
        $members = DataTables::of($resultSet)
            ->orderColumn('member_id', 'member_idINT $1')
            ->make(true);
        return api_response($members);
    }

    public function getMemberLookupList(Request $request, $orgId = null)
    {
        set_time_limit(300);
        $validationRules = [
//            'organization_id' => 'required|exists:organizations,id'
        ];
        $validator = Validator($request->all(), $validationRules);
        if ($validator->fails()) {
            return api_response(null, $validator->errors(), null, IResponseCode::INVALID_PARAMS);
        }
        /* @var $organization Organization */
        if (!empty($orgId)) {
            $organization = Organization::find($orgId);
        } else {
            $organization = Organization::find($request['organization_id']);
        }

        //region Organization not found response
        if (empty($organization)) {
            return api_error(['error' => 'Organization not found']);
        }
        //endregion

        $resultSet = $organization->members();

        if (!empty($request->name)) {
            $resultSet = $resultSet->where(function ($query) use ($request) {
                $query->where('first_name', 'like', '%' . $request->name . '%')->orWhere('last_name', 'like', '%' . $request->name . '%');
                $query->orWhere('member_id', 'like', '%' . $request->name . '%');
            });
        }
//        $resultSet->limit(200);

        $resultSet = $resultSet->select(['first_name', 'last_name', 'member_id', 'id']);

        $members = DataTables::of($resultSet)->make(true);
        return api_response($members);
    }

    public function saveGroup(Request $request)
    {

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'member_id' => 'required|exists:members,id',
//            'group_ids' => 'array',
            /*'group_ids.*' => 'exists:groupss,id',*/
            'type' => 'required|in:' . Group::TYPE['ADJUNCT'] . ',' . Group::TYPE['ACTIVITY'],
        ];

        $validator = Validator($request->all(), $validationRules);

        if (!$validator->fails()) {
            /* @var $organization Organization */
            $organization = $request->organization;
            /* @var $member Member */
            $member = Member::where('id', $request->member_id)->where('organization_id', $request->organization_id)->first();

            $groupIds = [];
            if (isset($request->group_ids)) {
                $groupIds = $request->group_ids;
            }
            $group = Group::whereIn('id', $groupIds)->where('organization_id', $request->organization_id)->where('type', $request->type)->get()->toArray();

            $validator->after(function ($validator) use ($member, $group, $request) {

                if (!$member) {
                    $validator->getMessageBag()->add('member_id', 'Member is not assosiated with this organization');
                }
                if (empty($group) && !empty($request->group_ids) && !empty($request->group_ids[0])) {
//                    $validator->getMessageBag()->add('group_id', 'Mismatch Group type or Ids ');
                }

            });

            if (!$validator->fails()) {

                if (!empty($groupIds)) {

                    //region detaching groups of same type and  in received.
                    DB::enableQueryLog();
                    $idsToDetach = DB::table('group_member')
                        ->join('groups', 'group_id', '=', 'groups.id')
                        ->where('type', $request->get('type'))
                        ->where('group_member.member_id', $request->get('member_id'))
                        ->whereNotIn('group_id', $groupIds)
                        ->pluck('groups.id')->toArray();

                    $groupsToDetach = $organization->groups()->whereIn('id', $idsToDetach)->get();

                    foreach ($groupsToDetach as $group) {
                        if (!empty($group->smsList)) {
                            $this->memberService->deleteMemberFromSmsList($organization, $group->smsList, $member);
                        }
                    }

                    DB::table('group_member')
                        ->join('groups', 'group_id', '=', 'groups.id')
                        ->where('type', $request->get('type'))
                        ->where('group_member.member_id', $request->get('member_id'))
                        ->whereNotIn('group_id', $groupIds)
                        ->delete();
                    //endregion

                    $alreadyAttatchGroupIds = $member->groups()->wherePivotIn('group_id', $groupIds)->pluck('groups.id')->toArray();

                    $idsToInsert = array_diff($groupIds, $alreadyAttatchGroupIds);

                    $groups = $organization->groups()->whereIn('id', $idsToInsert)->get();
                    foreach ($groups as $group) {
                        $smsList = $group->smsList;
                        if (empty($smsList)) {
                            $member->groups()->save($group);
                        } else {
                            //check sms list exist.
                            if ($member->type == Member::TYPE['MEMBER'])
//                                $smsListMember = $this->memberService->addToSmslist($organization, $smsList, $member, $group);
                            $member->groups()->save($group);
                        }
                    }
                } else {
                    // remove all groups
                    $groupIds = [];
                    $groupsToDetach = $member->groups()->where(['organization_id' => $organization->id])->select('groups.id')->get();
                    foreach ($groupsToDetach as $group) {
                        $groupIds[] = $group->id;
                        if (!empty($group->smsList)) {
                            $this->memberService->deleteMemberFromSmsList($organization, $group->smsList, $member);
                        }
                    }

                    // Deleting records of that member with empty array
                    DB::table('group_member')
                        ->join('groups', 'group_id', '=', 'groups.id')
                        ->where('type', $request->get('type'))
                        ->where('group_member.member_id', $request->get('member_id'))
                        ->whereIn('group_id', $groupIds)
                        ->delete();

                }
                $groups = $member->groups()/*->where('groups.type', $request->type)*/
                ->get();
                return api_response($groups, null, 'Successfully Added All Groups');
            } else {
                return response(ApiHelper::apiResponse(null, $validator->errors()), IResponseCode::INVALID_PARAMS);
            }
        } else {
            return api_response(null, $validator->errors(), 'Group not saved.', IResponseCode::INVALID_PARAMS);
        }

    }

    public function getInterests(Request $request)
    {
        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'type' => 'required|in:' . Group::TYPE['INTEREST'] . ',' . Group::TYPE['SKILL'],
            'member_id' => 'required|exists:members,id',
        ];

        $validator = Validator($request->all(), $validationRules);

        if (!$validator->fails()) {

            $type = $request->get('type');
            $validator->after(function ($validator) {

            });

            if (!$validator->fails()) {

                $member = Member::find($request->member_id);
                $memberInterests = $member->groups()->where('type', '=', $type)->get();
                return api_response($memberInterests);

            } else {
                return api_response(null, $validator->errors(), IResponseCode::INVALID_PARAMS);
            }
        } else {
            return api_response(null, $validator->errors(), IResponseCode::INVALID_PARAMS);
        }
    }

    public function createInterest(Request $request)
    {
        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'type' => 'required|in:' . Group::TYPE['INTEREST'] . ',' . Group::TYPE['SKILL'],
            'member_id' => 'required|exists:members,id',
            'name' => 'required'
        ];

        $validator = Validator($request->all(), $validationRules);

        if (!$validator->fails()) {

            $validator->after(function ($validator) {

            });

            if (!$validator->fails()) {
                $member = Member::find($request->member_id);

                $newGroup = new Group();
                $newGroup->name = $request->name;
                $newGroup->organization_id = $request->organization_id;
                $newGroup->status = IStatus::ACTIVE;
                $newGroup->type = $request->get('type');
                $newGroup->member_id = $request->get('member_id');
                $member->groups()->save($newGroup);

                return api_response($newGroup, null, 'Interest has been saved against member');
            } else {
                return api_response(null, $validator->errors(), IResponseCode::INVALID_PARAMS);
            }

        } else {
            return api_response(null, $validator->errors(), IResponseCode::INVALID_PARAMS);
        }
    }

    public function deleteInterest(Request $request)
    {
        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'type' => 'required|in:' . Group::TYPE['INTEREST'] . ',' . Group::TYPE['SKILL'],
            'member_id' => 'required|exists:members,id',
            'interest_id' => 'required|exists:groups,id'
        ];

        $validator = Validator($request->all(), $validationRules);

        if (!$validator->fails()) {
            $member = Member::find($request->member_id);
            $type = $request->get('type');
            $validator->after(function ($validator) use ($member, $request, $type) {
                if (empty($member)) {
                    $validator->getMessageBag()->add('member_id', 'Member not exists');
                } else {
                    $interest = $member->groups()->where('groups.id', $request->interest_id)->where('type', $type)->first();
                    if (empty($interest)) {

                        if ($type == Group::TYPE['INTEREST'])
                            $validator->getMessageBag()->add('interest_id', 'Interest not exists');
                        else if ($type == Group::TYPE['SKILL'])
                            $validator->getMessageBag()->add('interest_id', 'skill not exists');
                    }
                }
            });

            if (!$validator->fails()) {

                $interest = $member->groups()->where('groups.id', $request->interest_id)->where('type', $type)->first();

                $member->groups()->detach($request->interest_id);
                $interest->delete();
                $remainingIntrests = $member->groups()->where('type', '=', $type)->get();

                if ($type == Group::TYPE['INTEREST'])
                    return api_response($remainingIntrests, null, 'Interest has been removed');
                else if ($type == Group::TYPE['SKILL'])
                    return api_response($remainingIntrests, null, 'Skill has been removed');
            } else {
                return api_response(null, $validator->errors(), IResponseCode::INVALID_PARAMS);
            }

        } else {
            return api_response(null, $validator->errors(), IResponseCode::INVALID_PARAMS);
        }
    }

    public function changeOtherField(Request $request)
    {
        //region Base Validation Rules
        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'member_id' => 'required|exists:members,id',
        ];
        //endregion

        $fields = $request->input();

        //region If Fields is more then 1 return back
        if (count($fields) > 3) {
            return response(ApiHelper::apiResponse(null, ['error' => 'You can not edit more then one field']), IResponseCode::PRECONDITION_FAILED);
        }
        //endregion

        $validator = Validator($request->all(), $validationRules);

        //region Validating and updating the fields
        $memberFeild = null;
        $memberFieldValue = null;
        $validKey = false;

        if (!$validator->fails()) {

            //region Validating Field Name
            foreach ($fields as $key => $value) {
                if ($key != 'organization_id' && $key != 'member_id') {
                    if (in_array($key, MemberOther::AuthorizedFields())) {
                        $memberFeild = $key;
                        $memberFieldValue = $value;
                        $validKey = true;
                    } else {
                        $validator->getMessageBag()->add($key, 'Invalid Field Name');
                        return response(ApiHelper::apiResponse(null, $validator->errors()), IResponseCode::INVALID_PARAMS);
                    }
                }
            }
            //endregion

            if ($validKey) {

                $member = Member::where(['id' => $request->member_id, 'organization_id' => $request->organization_id])->first();
                if ($member) {
                    $memberOther = MemberOther::firstOrCreate(['member_id' => $request->member_id]);
                    $updatedMemberOther = $this->memberService->updateOtherField($memberOther, $memberFeild, $memberFieldValue);
                    $updatedMemberOther->refresh();
                    return api_response($updatedMemberOther, null, 'Member Details Updated Successfully');
                } else {
                    return response(ApiHelper::apiResponse(null, ['member_id' => 'Member not found'], IResponseCode::INVALID_PARAMS));
                }
            } else {
                return api_response(null, ['invalid_field' => 'invalid field']);
            }
        } else {
            return response(ApiHelper::apiResponse(null, $validator->errors()), IResponseCode::INVALID_PARAMS);
        }
        //endregion
    }

    public function getMemberNotesList($member_id, Request $request)
    {
        /* @var $organization Organization */
        $organization = $request->get(Organization::NAME);
        $member = Member::where([
            'organization_id' => $organization->id,
            'id' => $member_id,
        ])->first();

        if (empty($member)) {
            return api_error(['member_id' => 'Member not found']);
        }

        $notes = $member->notes()->orderBy('notes.id', 'desc')->get();
        return api_response($notes);
    }

    /**
     * Kiosk request request reponse.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function getMember(Request $request)
    {
        /* @var $organization Organization */
        $organization = $request->get(Organization::NAME);
        $kiosk = $organization->kiosks()->where('mac', $request->get('mac'))->first();
        $member = null;

        $query = \DB::table('members');
        $query->where('organization_id', $organization->id);
        $query->join('member_others', 'members.id', 'member_others.member_id');

        if (isset($request->member_id) && !empty($request->member_id)) {
            $query->where('members.member_id', $request->member_id);
            $query->whereNotNull('members.member_id');
        }

        if (isset($request->validate_id) && !empty($request->validate_id)) {
            $query->where('members.validate_id', $request->validate_id);
            $query->whereNotNull('members.validate_id');
        }

        if (isset($request->swipe_card_no) && !empty($request->swipe_card_no)) {
            $query->where('member_others.swipe_card', $request->swipe_card_no);
            $query->whereNotNull('member_others.swipe_card');
        }
        if (isset($request->prox_card_no) && !empty($request->prox_card_no)) {
            $query->where('member_others.prox_card', $request->prox_card_no);
            $query->whereNotNull('member_others.prox_card');
        }
        if(
            !empty($request->member_id) ||
            !empty($request->validate_id) ||
            !empty($request->swipe_card_no) ||
            !empty($request->prox_card_no)
        ){
            $query->select('members.id', 'members.first_name', 'members.last_name', 'member_others.points', 'members.renewal', 'members.member_id', 'members.validate_id');
            $member = $query->first();
        }
        $enabledDraws = (new OrganizationRepository())->getActiveDraws($organization);  // get active draw
        $enabledBirthdayVoucher = [];
        $birthdayVoucherSettings = [];
        if (!empty($kiosk)) {
            /**
             * @var $enabledBirthdayVoucher VoucherParameter
             */
            $enabledBirthdayVoucher = (new KioskRepository())->getActiveBirthdayVoucher($organization);

            /* @var $birthdayVoucherSettings KioskVoucherParameter */
            $birthdayVoucherSettings = $organization->kioskVoucherParameter;    //get assigned birthday voucher and setting related to that.
        }

        $enteredDraws = [];
        if (!empty($member)) {
            $memberInstance = Member::find($member->id);

            foreach ($enabledDraws as $enabledDraw) {
                $draw = $this->memberService->addMemberToDraw($memberInstance, $enabledDraw);
                //region Manipulating response for the kiosk
                if (!empty($draw)) {

                    $prize = $draw->prize;
                    $prizeTitle = (!empty($prize))? $prize->name: '';
                    $enteredDraws[] =
                        [
                            'name' => $draw->name,
                            'prize' => $prizeTitle,
                            'print' => $draw->print_entry,
                        ];
                }
                //endregion
            }
            $memberBirthday = $memberInstance->date_of_birth;
            $currentDate = Carbon::now();

            $shouldGenerateVoucherByFrequency = false;
            $shouldGenerateVoucher = false;

            if (!empty($memberBirthday)) {

                $memberCarbonBirthday = new Carbon($memberBirthday);
                $memberCarbonBirthday = new \Carbon\Carbon (date($memberCarbonBirthday->day . '-' . $memberCarbonBirthday->month . '-Y'));


                if (!empty($birthdayVoucherSettings)) {
                    $frequency = $birthdayVoucherSettings->frequency;
                    $durationStart = Carbon::now();
                    $durationEnd = Carbon::now();

                    switch ($birthdayVoucherSettings->duration) {
                        case KioskVoucherParameter::DURATION['DAY_OF_BIRTHDAY']:
                            $durationStart = Carbon::now()->startOfDay();
                            $durationEnd = Carbon::now()->endOfDay();
                            break;
                        case KioskVoucherParameter::DURATION['MONTH_OF_BIRTHDAY']:
                            $durationStart = Carbon::now()->startOfMonth()->startOfDay();

                            $durationEnd = Carbon::now()->endOfMonth()->endOfDay();
                            break;
                        case KioskVoucherParameter::DURATION['WEEK_OF_BIRTHDAY']:
                            $durationStart = Carbon::now()->startOfWeek()->startOfDay();
                            $durationEnd = Carbon::now()->endOfWeek()->endOfDay();
                            break;
                        case KioskVoucherParameter::DURATION['DAYS_EITHER_SIDE']:
                            $durationStart = Carbon::now()->addDays($birthdayVoucherSettings->days_before)->startOfDay();
                            $durationEnd = Carbon::now()->addDays($birthdayVoucherSettings->days_after)->endOfDay();
                            break;
                        default:
                            $durationStart = null;
                            $durationEnd = null;
                            break;
                    }

                    if ($memberCarbonBirthday >= $durationStart && $memberCarbonBirthday <= $durationEnd) {
                        $shouldGenerateVoucher = true;
                    } else {
                        $shouldGenerateVoucher = false;
                    }

                    if ($shouldGenerateVoucher) {
                        switch ($frequency) {
                            case KioskVoucherParameter::FREQUENCY['ONCE_A_DAY']:

                                $voucherCount = Voucher::whereCustomerEmail($memberInstance->email)
                                    ->where('member_id', $memberInstance->id)
                                    ->where('voucher_parameter_id', $enabledBirthdayVoucher->id)
                                    ->whereBetween('created_at', [Carbon::now()->startOfDay()->format('Y-m-d H:i:s'), Carbon::now()->endOfDay()->format('Y-m-d H:i:s')])
                                    ->count();

                                if ($voucherCount < 1) {
                                    $shouldGenerateVoucherByFrequency = true;
                                } else {
                                    $shouldGenerateVoucherByFrequency = false;
                                }
                                break;
                            case KioskVoucherParameter::FREQUENCY['ONCE_ONLY']:
                                $voucherCount = Voucher::whereCustomerEmail($memberInstance->email)
                                    ->where('member_id', $memberInstance->id)
                                    ->where('voucher_parameter_id', $enabledBirthdayVoucher->id)->count();
                                if ($voucherCount < 1) {
                                    $shouldGenerateVoucherByFrequency = true;
                                } else {
                                    $shouldGenerateVoucherByFrequency = false;
                                }
                                break;
                            case KioskVoucherParameter::FREQUENCY['ONCE_A_WEEK']:
                                $voucherCount = Voucher::whereCustomerEmail($memberInstance->email)
                                    ->where('member_id', $memberInstance->id)
                                    ->whereBetween('created_at', [Carbon::now()->startOfDay()->startOfWeek()->format('Y-m-d h:i:s'), Carbon::now()->endOfWeek()->endOfDay()->format('Y-m-d H:i:s')])
                                    ->where('voucher_parameter_id', $enabledBirthdayVoucher->id)
                                    ->count();
                                if ($voucherCount < 1) {
                                    $shouldGenerateVoucherByFrequency = true;
                                } else {
                                    $shouldGenerateVoucherByFrequency = false;
                                }
                                break;
                            case KioskVoucherParameter::FREQUENCY['ONCE_A_MONTH']:
                                $voucherCount = Voucher::whereCustomerEmail($memberInstance->email)
                                    ->where('member_id', $memberInstance->id)
                                    ->whereBetween('created_at', [Carbon::now()->startOfDay()->subMonth(1)->format('Y-m-d h:i:s'), Carbon::now()->endOfDay()->format('Y-m-d H:i:s')])
                                    ->where('voucher_parameter_id', $enabledBirthdayVoucher->id)
                                    ->count();
                                if ($voucherCount < 1) {
                                    $shouldGenerateVoucherByFrequency = true;
                                } else {
                                    $shouldGenerateVoucherByFrequency = false;
                                }
                                break;
                            default:
                                $shouldGenerateVoucherByFrequency = false;
                        }
                    }
                }
            }

            if ($shouldGenerateVoucherByFrequency && $shouldGenerateVoucher) {

                /**
                 * @var $voucherRepo VoucherRepository
                 */
                $voucherRepo = new VoucherRepository();
                $data = [
                    'customer_name' => $memberInstance->first_name . ' ' . $memberInstance->last_name,
                    'customer_email' => $memberInstance->email,
                ];
                $data['member_id'] = $memberInstance->id;
                $voucher = $voucherRepo->generateVoucher($organization, $enabledBirthdayVoucher, $data);
            }
        }


        if (!empty($member)) {
            $member->points = number_format($member->points, 2);
        }
        $result['member'] = $member;
        $result['entered_draws'] = $enteredDraws;

        $result['birthday_voucher'] = (!empty($voucher)) ? $voucher : [];

        return api_response($result);
    }

    public function getUpdatedPoints(Request $request)
    {
        /* @var $organization Organization */
        $organization = $request->get(Organization::NAME);
        $member = null;

        $query = \DB::table('members');
        $query->where('organization_id', $organization->id);
        $query->join('member_others', 'members.id', 'member_others.member_id');

        if (isset($request->member_id) && !empty($request->member_id)) {
            $query->where('members.member_id', $request->member_id);
            $query->whereNotNull('members.member_id');
        }

        if (isset($request->validate_id) && !empty($request->validate_id)) {
            $query->where('members.validate_id', $request->validate_id);
            $query->whereNotNull('members.validate_id');
        }

        if (isset($request->swipe_card_no) && !empty($request->swipe_card_no)) {
            $query->where('member_others.swipe_card', $request->swipe_card_no);
            $query->whereNotNull('member_others.swipe_card');
        }
        if (isset($request->prox_card_no) && !empty($request->prox_card_no)) {
            $query->where('member_others.prox_card', $request->prox_card_no);
            $query->whereNotNull('member_others.swipe_card');
        }
        if(
            !empty($request->member_id) ||
            !empty($request->validate_id) ||
            !empty($request->swipe_card_no) ||
            !empty($request->prox_card_no)
        ){
            $query->select('members.id', 'members.first_name', 'members.last_name', 'member_others.points', 'members.renewal', 'members.member_id', 'members.validate_id');
            $member = $query->first();
        }
        $result = null;
        $message = null;
        if (!empty($member)) {
            $memberInstance = Member::find($member->id);
            $member->points = number_format($member->points, 2);
            $memberPoints = $this->memberService->getUpdatedMemberPoints($organization ,$memberInstance);

            $message = 'Points update failed, try again.';
           if(!empty($memberPoints)){
               $memberOther = $memberInstance->others;
               if(!empty($memberOther)){
                   $memberOther->points = $memberPoints/100;
                   $memberOther->save();
               }
               $message = 'Points updated successfully';
           }
            $result = (!empty($memberPoints)) ? $memberPoints: $member->points;
        }


        return api_response($result, null,$message);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ApiException
     */
    public function setTemplate(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'organization_id' => 'required',
            'member_id' => 'required|exists:members,id',
            'template_id' => 'required|exists:organization_card_templates,id'
        ];

        $validator = Validator($request->all(), $validationRules);

        if (!$validator->fails()) {
            $member = $organization->members()->find($request->member_id);
            /** @var OrganizationCardTemplate $template */
            $template = $organization->templates()->find($request->template_id);
            $validator->after(function ($validator) use ($member, $template) {
                if (!$member) {
                    $validator->getMessageBag()->add('member_id', 'Invalid Member');
                }
                if (!$template) {
                    $validator->getMessageBag()->add('template_id', 'Invalid Card Template');
                }
            });

            if (!$validator->fails()) {
                $member->organization_card_template_id = $template->id;
                $member->member_id_card = $this->memberService->generateMemberCard($template, $member);
                $member->save();
                $member = $organization->members()->where('id', '=', $request->member_id)
                    ->with('physicalAddress', 'template', 'postalAddress', 'groups', 'others')->first();
                return api_response($member);
            } else {
                return api_error($validator->errors());
            }

        } else {
            return api_error($validator->errors());
        }
    }

    public function addSubscription(Request $request)
    {

        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'member_id' => 'required|exists:members,id',
            'subscription_id' => 'required|exists:subscriptions,id',
        ];

        $validator = Validator($request->all(), $validationRules);

        if (!$validator->fails()) {
            $subscription = $organization->subscriptions()->find($request->get('subscription_id'));
            $member = $organization->members()->find($request->get('member_id'));
            $validator->after(function ($validator) use ($member, $subscription) {
                if (empty($member)) {
                    $validator->getMessageBag()->add('member_id', 'No member for this organization');
                }
                if (empty($subscription)) {
                    $validator->getMessageBag()->add('subscription_id', 'No subscription for this organization');
                }
            });

            if (!$validator->fails()) {
                $updatedMember = $this->memberService->addSubscription($member, $subscription);
                return api_response($updatedMember);
            } else {
                return api_error($validator->errors());
            }

        } else {
            return api_error($validator->errors());
        }
    }

    public function uploadIdentity(Request $request)
    {

        /** @var $organization Organization */

        $organization = $request->get(Organization::NAME);

        $allowedImageExtensionArray = ['jpeg', 'bmp', 'png', 'JPG'];
        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'member_id' => 'required|exists:members,id',
            'identity' => 'mimes:' . implode(",", $allowedImageExtensionArray) . '|max:4000',
        ];

        $validator = Validator($request->all(), $validationRules);

        if (!$validator->fails()) {
            /** @var Member $member */
            $member = $organization->members()->find($request->member_id);
            $validator->after(function ($validator) use ($member) {
                if (!$member) {
                    $validator->getMessageBag()->add('mamber_id', 'Invalid Member Id');
                }
            });

            if (!$validator->fails()) {
                $file = $request->file('identity');
                $name = $file->getClientOriginalName();
                $name = md5($name) . '.' . $file->getClientOriginalExtension();
                $path = '/members_identity/' . $name;
                Storage::put($path, File::get($file->getRealPath()));
                $url = Storage::disk('local')->url($path);

                $member->identity = $url;
                if ($member->template) {
                    $member->member_id_card = $this->memberService->generateMemberCard($member->template, $member);
                }
                $member->update();
                return api_response($member);

            } else {
                return api_error($validator->errors());
            }

        } else {
            return api_error($validator->errors());
        }
    }

    public function searchField(Request $request)
    {
        $authorizedMemberFileds = Member::AUTHORISED_FIELDS;    //members table authorised fields
        $authorisedMemberOtherFields = MemberOther::AuthorizedFields();     //member_others table authorised fields
        $authorisedAddressFields = Member::ADDRESS_FIELDS;  //address table authorised fields

        $authorisedFields = array_merge($authorizedMemberFileds, $authorisedMemberOtherFields);   //all authorise
        $authorisedFields = array_merge($authorisedFields, $authorisedAddressFields);   //all authorise

        if ($request->filled('name')) {
            $input = preg_quote($request->get('name'), '~');
            $authorisedFields = preg_grep('~' . $input . '~', $authorisedFields);
        }
        return api_response($authorisedFields);
    }

    public function login(Request $request)
    {

        $validationRules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        $validator = Validator($request->all(), $validationRules);

        if (!$validator->fails()) {

            $email = $request->get('email');
            $password = $request->get('password');
            $member = Member::where(['email' => $email, 'password' => md5($password)])->first();
            $validator->after(function ($validator) use ($member) {
                if (empty($member)) {
                    $validator->getMessageBag()->add('auth', 'Invalid Credentials');
                }
            });
            if (!$validator->fails()) {
                if ($member->verify != IStatus::ACTIVE) {
                    //verification check
                    throw new ApiException(null, ['error' => ['you have pending verification please verify your email before login.']], null, IResponseCode::NOT_ENOUGH_PERMISSIONS);
                }

                //updating last info.

//                Member::whereEmail($email)->update(['last_login' => Carbon::now()]);

                $memberId = null;
                $qrCodeLink = null;
                $members = Member::whereEmail($email)->get();
                foreach ($members as $item) {
                    $organization = $item->organization;
                    $timezone = null;
                    if (!empty($organization->timezone->timezone)) {
                        try {
                            $timezone = $organization->timezone->timezone;
                        } catch (\Exception $exception) {
                                                                                                                          
                        }
                    }
                    if(empty($memberId)){
                        $memberId = $item->validate_id;
                    }
                    $qrCodeLink = $item->qr_code;
                    $item->last_login = Carbon::now($timezone);
                    $item->save();
                }
                if (!$memberId) {
                    $memberId = $this->memberService->generateValidateId();
                    $qrCodeLink = qrCodeGenerate('member-qr-codes-', $memberId);
                }

                Member::whereEmail($email)->update(['validate_id' => $memberId, 'qr_code' => $qrCodeLink]);
                $member->refresh();
                if (!empty($member)) {
                    foreach (Member::whereEmail($email)->get() as $memberItem) {
                        if (!empty($memberItem->validate_id)) {
                            $organizationRepo = new OrganizationRepository();

                            // as the  validate id is updated so push member to pos and then add member card.
                            if ($memberItem->validate_id &&  $organizationRepo->verifyUntillSetting($organization)
                                && $memberItem->type !== Member::TYPE['CONTACT']) {
                                if (!$memberItem->untill_id) {
                                    try {
                                        $this->memberService->pushmemberToPos($memberItem->organization, $memberItem);
                                    }catch (ApiException $exception){
                                        continue;
                                    }
                                }
                                // check if there is Member card Id in member untill_data if it is, don't push it
                                $untillMemberCardId = $this->memberService->checkIfClientCardExists($memberItem->organization, $memberItem, Member::CARD_NAME['MEMBERME_ID']);
                                if (empty($untillMemberCardId) && $memberItem->untill_id) {
                                    try{
                                        $this->memberService->pushCardToPos($memberItem, $memberItem->organization, Member::CARD_NAME['MEMBERME_ID'], $memberItem->validate_id);
                                        $this->memberService->updateMemberCardId($memberItem->organization, $memberItem);
                                    } catch (ApiException $e) { continue;
                                    }
                                }
                            }

                            $profile = $memberItem->profile;
                            if (!empty($profile)) {
                                $profile->validate_id = $memberItem->validate_id;
                                $profile->save();
                            }
                        }
                    }

                    return api_response(['profile' => $member->profile()->with('physicalAddress')->first(), 'api_token' => $member->api_token]);
                }
            } else {
                return api_error($validator->errors());
            }
        } else {
            return api_error($validator->errors());
        }
    }

    public function organiztionList(Request $request)
    {
        $member = $request->get('member');
        $organization_ids = Member::whereEmail($member->email)->pluck('organization_id')->toArray();
        $organizations = Organization::where('id', '!=', Config::get('global.MEMBERME_ID'))->whereIn('id', $organization_ids)->get();
        return api_response($organizations);
    }

    public function getMemberTransactionList(Request $request)
    {
        /** @var $organization Organization */

        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'id' => 'required|exists:members,id',
            'organization_id' => 'required|exists:organizations,id'
        ];

        $validator = Validator($request->all(), $validationRules);

        if (!$validator->fails()) {

            $validator->after(function ($validator) {
                //todo After Validation here
            });

            if (!$validator->fails()) {

                $transactions = $organization->transactions()->whereHas('payments', function ($query) use ($request) {
                    $query->where([
                        'item_id' => $request->get('id'),
                        'item_type' => Payment::ITEM_TYPE['MEMBER']
                    ]);
                })->with(['receipt', 'payer', 'payments.subscription', 'organization' => function ($query) {
                    $query->select('organizations.id', 'organizations.name');
                    $query->with(['details' => function ($detailsQuery) {
                        $detailsQuery->select(
                            'organization_details.organization_id',
                            'organization_details.physical_address_id',
                            'gst_number'
                        );
                        $detailsQuery->with(['physicalAddress' => function ($addressQuery) {
                            $addressQuery->select('addresses.*');
                        }]);
                    }]);
                }/*, 'organizationDetails' => function($q){ $q->select('organization_id', 'gst_number'); }*/])
                    ->orderBy('transactions.created_at', 'desc')
                    ->get();

                return api_response($transactions);
            } else {
                return api_error($validator->errors());
            }

        } else {
            return api_error($validator->errors());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ApiException
     * @throws \SendGrid\Mail\TypeException
     */
    public function sendResetEmail(Request $request)
    {
        /* @var $member Member */
        $member = Member::whereEmail($request->get('email'))
            ->first();

        if (empty($member)) {
            return api_error(['email' => ['Member not found against this Email']]);
        }
        try {
            $member->verify_token = Str::random(60);
            $member->save();
        } catch (\Exception $exception) {
            \Log::info($exception->getMessage());
        }

        $this->memberService->sendResetPasswordEmail($member);
        return api_response(null, null, 'Reset email has been sent to your mail, please follow the instructions');
    }

    public function verifyMemberEmail(Request $request)
    {
        $member = Member::where([
            'member_id' => $request->get('member_id'),
            'organization_id' => $request->get('organization_id'),
        ])->first();

        if (empty($member)) {
            return api_error(['member_id' => 'Invalid Member Id']);
        }

        if ($member->email != $request->get('email')) {
            return api_error(['email' => 'Invalid email']);
        }

        // as the  validate id is updated so push member to pos and then add member card.
        if(!$member->untill_id && $member->validate_id){
            $this->memberService->pushmemberToPos($member->organization, $member);
            $this->memberService->pushCardToPos($member,$member->organization,Member::CARD_NAME['MEMBERME_ID'],$member->validate_id);
            $this->memberService->updateMemberCardId($member->organization,$member);
        }

        return api_response($member->email, null, 'Email Verified');
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Database\Eloquent\Model|\Illuminate\Http\JsonResponse
     * @throws ApiException
     */
    public function signUp(Request $request)
    {
        $validationRules = [
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'password' => 'required|confirmed|min:6',
            'email' => 'required|email'
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }
        $member = Member::where('email', $request->get('email'))->first();

        if (!empty($member)) {
            return api_error([
                'email' => [
                    'Member with same email already exists'
                ]
            ]);
        }

        $data = $request->all();
        $data['organization_id'] = Config::get('global.MEMBERME_ID');
        $newMember = $this->memberService->addMember($data, false, false, true);

        $alreadyMember = Member::whereEmail($newMember->email)->first();
        if (!empty($alreadyMember)) {
            $validateNumber = $alreadyMember->validate_id;
        }

        if (empty($validateNumber)) {
            $newMember->validate_id = $this->memberService->generateValidateId();
            $newMember->qr_code = qrCodeGenerate('member-qr-codes-' . $newMember->organization_id, $newMember->validate_id);
        } else {
            $newMember->validate_id = $validateNumber;
            $newMember->qr_code = qrCodeGenerate('member-qr-codes-' . $newMember->organization_id, $newMember->validate_id);
        }

        $newMember->save();
        if (!empty($newMember->profile)) {
            $profile = $newMember->profile;
            $profile->validate_id = $newMember->validate_id;
            $profile->save();
        }

        if ($newMember->verify != IStatus::ACTIVE) {
            return api_response(null, null, 'Registration successful, please check your email to verify the account.');
        }
        return api_response($newMember);
    }

    /**
     * Update member personal profile.Api for member application.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePersonalProfile(Request $request)
    {
        /* @var $member Member */
        $member = $request->get('member');

        $validationRules = [
            'email' => 'email',
            'gender' => 'in:Male,Female,Not Specified',
            'contact_no' => 'numeric',
            'first_name' => 'string',
            'last_name' => 'string',
            'date_of_birth' => 'date_format:d/m/Y',
            'country' => 'numeric|exists:countries,id',
            'city' => 'string|min:2',
            'address' => 'string|min:3',
            'postal_code' => 'numeric|min:2',

        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        if (!empty($request->get('email'))) {
            $email = $request->get('email');
            $memberWithEmail = Member::whereEmail($email)->whereOrganizationId(array_get($member, 'organization_id'))->where('id', '!=', $member->id)->first();
            if (!empty($memberWithEmail)) {
                return api_error(['email' => [
                    'Member with same email already exists'
                ]
                ]);
            }
        }

        $profile = $member->profile;
        $changedFields = [];

        if (!empty($request->get('first_name')) && $profile->first_name != $request->get('first_name')) {
            $changedFields[] = [
                'name' => 'First Name',
                'field_name' => 'first_name',
                'old_value' => array_get($profile, 'first_name'),
                'new_value' => $request->get('first_name'),
            ];
        }

        if (!empty($request->get('last_name')) && $profile->last_name != $request->get('last_name')) {
            $changedFields[] = [
                'name' => 'Last Name',
                'field_name' => 'last_name',
                'old_value' => array_get($profile, 'last_name'),
                'new_value' => $request->get('last_name'),
            ];
        }

//        $request->get('date_of_birth');
        $dashedDob = str_replace('/', '-', $request->get('date_of_birth'));
        $dateToMatch = date('Y-m-d', strtotime($dashedDob));
        $country = Country::find($request->get('country'));
        $physicalAddress = ($profile) ? $profile->physicalAddress : null;
        if (!empty($request->get('date_of_birth')) && $profile->date_of_birth != $dateToMatch) {
            $changedFields[] = [
                'name' => 'Date of Birth',
                'field_name' => 'date_of_birth',
                'old_value' => array_get($profile, 'date_of_birth'),
                'new_value' => $dateToMatch,
            ];
        }

        if (!empty($request->get('contact_no')) && $profile->contact_no != $request->get('contact_no')) {
            $changedFields[] = [
                'name' => 'Contact No',
                'field_name' => 'contact_no',
                'old_value' => array_get($profile, 'contact_no'),
                'new_value' => $request->get('contact_no'),
            ];
        }

        if (!empty($request->get('email')) && $profile->email != $request->get('email')) {
            $changedFields[] = [
                'name' => 'E-mail',
                'field_name' => 'email',
                'old_value' => array_get($profile, 'email'),
                'new_value' => $request->get('email'),
            ];
        }

        if (!empty($request->get('gender')) && $profile->gender != $request->get('gender')) {
            $changedFields[] = [
                "name" => "Gender",
                "field_name" => "gender",
                "old_value" => array_get($profile, 'gender'),
                "new_value" => $request->get('gender'),
            ];
        }

        if (!empty($request->get('known_as')) && $profile->known_as != $request->get('known_as')) {
            $changedFields[] = [
                "name" => "Known As",
                "field_name" => "known_as",
                "old_value" => array_get($profile, 'known_as'),
                "new_value" => $request->get('known_as'),
            ];
        }

        if (!empty($request->get('middle_name')) && $profile->middle_name != $request->get('middle_name')) {
            $changedFields[] = [
                "name" => "Middle Name",
                "field_name" => "middle_name",
                "old_value" => array_get($profile, 'middle_name'),
                "new_value" => $request->get('middle_name'),
            ];
        }

        if (!empty($request->get('title')) && $profile->title != $request->get('title')) {
            $changedFields[] = [
                "name" => "Title",
                "title" => "title",
                "old_value" => array_get($profile, 'title'),
                "new_value" => $request->get('title'),
            ];
        }

        if (!empty($request->get('facebook_id')) && $profile->facebook_id != $request->get('facebook_id')) {
            $changedFields[] = [
                "name" => "Facebook_id",
                "field_name" => "facebook_id",
                "old_value" => array_get($profile, 'facebook_id'),
                "new_value" => $request->get('facebook_id'),
            ];
        }

        if (!empty($request->get('phone')) && $profile->phone != $request->get('phone')) {
            $changedFields[] = [
                "name" => "Phone",
                "field_name" => "phone",
                "old_value" => array_get($profile, 'phone'),
                "new_value" => $request->get('phone'),
            ];
        }

        if (!empty($physicalAddress)) {
            if (!empty($request->get('country')) && (empty($physicalAddress->country->name) || $physicalAddress->country_id != $request->get('country'))) {
                $changedFields[] = [
                    "name" => "Country",
                    "field_name" => "country",
                    "old_value" => !empty($physicalAddress->country->name) ? $physicalAddress->country->name : '',
                    "new_value" => $country->name,
                ];
            }

            if (!empty($request->get('address')) && $physicalAddress->address1 != $request->get('address')) {
                $changedFields[] = [
                    "name" => "Address",
                    "field_name" => "address",
                    "old_value" => $physicalAddress->address1 ?? '',
                    "new_value" => $request->get('address'),
                ];
            }
            if (!empty($request->get('city')) && $physicalAddress->city != $request->get('city')) {
                $changedFields[] = [
                    "name" => "City",
                    "field_name" => "city",
                    "old_value" => $physicalAddress->city ?? '',
                    "new_value" => $request->get('city'),
                ];
            }
            if (!empty($request->get('postal_code')) && $physicalAddress->postal_code != $request->get('postal_code')) {
                $changedFields[] = [
                    "name" => "Postal Code",
                    "field_name" => "postal_code",
                    "old_value" => $physicalAddress->postal_code ?? '',
                    "new_value" => $request->get('postal_code'),
                ];
            }
        } else {
            if (!empty($request->get('country'))) {
                $changedFields[] = [
                    "name" => "Country",
                    "field_name" => "country",
                    "old_value" => '',
                    "new_value" => $country->name,
                ];
            }
            if (!empty($request->get('address'))) {
                $changedFields[] = [
                    "name" => "Address",
                    "field_name" => "address",
                    "old_value" => '',
                    "new_value" => $request->get('address'),
                ];
            }
            if (!empty($request->get('city'))) {
                $changedFields[] = [
                    "name" => "City",
                    "field_name" => "city",
                    "old_value" => '',
                    "new_value" => $request->get('city'),
                ];
            }
            if (!empty($request->get('postal_code'))) {
                $changedFields[] = [
                    "name" => "Postal Code",
                    "field_name" => "postal_code",
                    "old_value" => '',
                    "new_value" => $request->get('postal_code'),
                ];
            }
        }

        $updatedProfile = $this->memberService->updatePersonalProfile($profile, $request->all());
        $members = Member::whereEmail($member->email)->get();
        if (!empty($changedFields)) {
            foreach ($members as $member) {
                $memberNotification = new MemberNotification();
                $memberNotification->added_by_id = $member->member_id;
                $memberNotification->member_id = $member->id;
                $memberNotification->changed_fields = $changedFields;
                $memberNotification->added_by_type = MemberNotification::TYPE["MEMBER_ID"];
                $memberNotification->organization_id = $member->organization_id;
                $memberNotification->status = MemberNotification::TYPE["STATUS"];
                $memberNotification->save();
                event(New MemberProfileChange($member, $memberNotification));
            }

        }

        $message = null;
        if (!empty($request->get('email'))) {
            $message = 'Please check your email for verification.';
        }
        return api_response(['profile' => $updatedProfile], null, $message);
    }

    /**
     * [ val-30-07 ] Member Template Details and style
     * members/get-member-cards
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMemberWithTemplates(Request $request)
    {
        /* @var  $member Member */
        $member = $request->get('member');

        $result = Member::where('email', '=', $member->email)
            ->where('organization_id', '!=', Config::get('global.MEMBERME_ID'))
            ->whereNotNull('member_id_card')
            ->select('member_id_card')
            ->get();

        return api_response($result);

    }

    public function verifyMemberAccount($token, Request $request)
    {
        /* @var $member Member */
        $member = Member::whereVerifyToken($token)->first();

        if (empty($member)) {
            return api_error(['error' => 'Invalid Token']);
        }

        $member->verify = IStatus::ACTIVE;
        $member->save();

        return view('member.verified');
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createEmployment(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'member_id' => 'required|exists:members,id',
            'date_to' => 'required',
            'date_from' => 'required',
            'employer' => 'string|required',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        //region Member Verification
        $member = $organization
            ->members()
            ->where('members.id', $request->get('member_id'))
            ->first();
        if (empty($member)) {
            return api_error(['member_id' => 'Member not found']);
        }
        //endregion

        $this->memberService->createEmployment($request->all());

        $memberEmployments = $member->employment;
        return api_response($memberEmployments);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createEducation(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'member_id' => 'required|exists:members,id',
            'date_to' => 'required',
            'date_from' => 'required',
            'institution' => 'string|required',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        //region Member Verification
        $member = $organization
            ->members()
            ->where('members.id', $request->get('member_id'))
            ->first();
        if (empty($member)) {
            return api_error(['member_id' => 'Member not found']);
        }
        //endregion

        $this->memberService->createEducation($request->all());

        $memberEmployments = $member->education;
        return api_response($memberEmployments);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllEmployments(Request $request)
    {
        /** @var $organization Organization */

        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'member_id' => 'required|exists:members,id'
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        //region Member Verification

        /* @var  $member Member */
        $member = $organization
            ->members()
            ->where('members.id', $request->get('member_id'))
            ->first();
        if (empty($member)) {
            return api_error(['member_id' => 'Member not found']);
        }
        //endregion

        $memberEmployments = $member->employment;
        return api_response($memberEmployments);

    }

    public function getAllEducations(Request $request)
    {
        /** @var $organization Organization */

        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'member_id' => 'required|exists:members,id'
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        //region Member Verification

        /* @var  $member Member */
        $member = $organization
            ->members()
            ->where('members.id', $request->get('member_id'))
            ->first();
        if (empty($member)) {
            return api_error(['member_id' => 'Member not found']);
        }
        //endregion

        $memberEducations = $member->education;
        return api_response($memberEducations);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeEmployment(Request $request)
    {
        /** @var $organization Organization */

        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'member_id' => 'required|exists:members,id',
            'id' => 'required|exists:member_employments,id'
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        //region Member Verification

        /* @var  $member Member */
        $member = $organization
            ->members()
            ->where('members.id', $request->get('member_id'))
            ->first();
        if (empty($member)) {
            return api_error(['member_id' => 'Member not found']);
        }
        //endregion

        $member->employment()
            ->where('id', $request->get('id'))
            ->delete();

        $memberEmployments = $member->employment;
        return api_response($memberEmployments, null, 'Employment Removed Successfully');
    }

    public function removeEducation(Request $request)
    {

        /** @var $organization Organization */

        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'member_id' => 'required|exists:members,id',
            'id' => 'required|exists:member_educations,id'
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        //region Member Verification

        /* @var  $member Member */
        $member = $organization
            ->members()
            ->where('members.id', $request->get('member_id'))
            ->first();
        if (empty($member)) {
            return api_error(['member_id' => 'Member not found']);
        }
        //endregion

        $member->education()
            ->where('id', $request->get('id'))
            ->delete();

        $memberEducation = $member->education;
        return api_response($memberEducation, null, 'Education Removed Successfully');
    }

    public function getSentMessages(Request $request)
    {

        /** @var $organization Organization */

        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'id' => 'required|exists:members,id'
        ];

        $member = $organization->members()->where('id', $request->get('id'))->first();
        if (empty($member)) {
            return api_error(['member_id' => 'Invalid member_id']);
        }


        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }
        $messages = $this->memberService->getMemberMessages($member);
        return api_response($messages);
    }

    public function verifyChangeEmail($token)
    {
        $memberProfile = MemberProfile::where('verify_token', $token)->first();
        if (!empty($memberProfile) && $memberProfile->verify_link_sent_date_time) {
            $now = Carbon::now();
            $verifyLinkSentDate = new Carbon($memberProfile->verify_link_sent_date_time);
            $diff = $now->diff($verifyLinkSentDate);
            if ($diff->days >= 1) {
                return api_error(['error' => 'Link Expired. Please retry']);
            }

        } else {
            return api_error(['error' => 'Invalid token']);
        }
        if (!empty($memberProfile)) {
            $emailToChange = $memberProfile->email_to_change;
            if (!empty($emailToChange)) {
                Member::whereEmail($memberProfile->email)->update(['email' => $emailToChange]);
                MemberProfile::whereEmail($memberProfile->email)->update(['email' => $emailToChange]);
                $memberProfile->email = $emailToChange;
//                $memberProfile->email_to_change = null;
                $memberProfile->save();
            }
            return 'Your email has been updated. Please use your new email at login.';
        }
        return 'We cannot find any member to change profile. Please contact administrator.';
    }

    public function updatePassword(Request $request)
    {
        /* @var $member Member */
        $member = $request->get('member');

        $validationRules = [
            'current_password' => 'required',
            'password' => 'required|confirmed|string|min:6'
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        if ($member->password != md5($request->get('current_password'))) {
            return api_error([
                'auth' => ['Invalid Current Password']
            ]);
        }

        $member->password = md5($request->get('password'));
        $member->save();
        return api_response(null, null, 'Password has been changed');
    }

    public function memberResetPassword($token)
    {
        $data = explode('-_-', $token);

        $member = Member::where([
            'verify_token' => $data[0],
            'email' => base64_decode($data[1]),
        ])->first();

        if (empty($member)) {
            return 'Invalid Member or Token Expired';
        }
        return view('member.resetPassword', ['member' => $member]);
    }

    public function resetMemberPassword(Request $request)
    {
        $validationRules = [
            'token' => 'required|exists:members,verify_token',
            'email' => 'required|email',
            'password' => 'required|string|confirmed|min:6'
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }
        $member = Member::where([
            'verify_token' => $request->get('token'),
        ])->first();

        if (empty($member)) {
            return "<h6 align='center'> Invalid Token </h6>";
        }


        if ($member->email != $request->get('email')) {
            return "<h6 align='center'> Please recheck your email. </h6>";
        }

        $password = md5($request->get('password'));
        $apiToken = Str::random(60);
        $member->password = $password;
        $member->api_token = $apiToken;
        $members = Member::where(['email' => $member->email])->get();
        foreach ($members as $item) {   //updating all passwords.
            $item->password = $password;
            $item->api_token = Str::random(60);
            $item->verify = IStatus::ACTIVE;
            $item->save();
        }
        $member->save();

        return view('email.member.passwordUpdated');

    }

    public function updateMemberPaymentInfo(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'member_id' => 'required|exists:members,id',
        ];

        $fields = $request->input();

        //region If Fields is more then 1 return back
        if (count($fields) > 3) {
            return response(ApiHelper::apiResponse(null, ['error' => 'You can not edit more then one field']), IResponseCode::PRECONDITION_FAILED);
        }
        //endregion

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        $memberdirectPaymentDetailFeild = null;
        $memberdirectPaymentDetailFieldValue = null;
        $validKey = false;

        foreach ($fields as $key => $value) {
            if ($key != 'organization_id' && $key != 'member_id') {
                if (in_array($key, MemberDirectPaymentDetails::AuthorizedFields())) {
                    $memberdirectPaymentDetailFeild = $key;
                    $memberdirectPaymentDetailFieldValue = $value;
                    $validKey = true;
                } else {
                    $validator->getMessageBag()->add($key, 'Invalid Field Name');
                    return response(ApiHelper::apiResponse(null, $validator->errors()), IResponseCode::INVALID_PARAMS);
                }
            }
        }

        if ($validKey) {
            /**
             * @var $member Member
             */
            $member = $organization->members()->where('id', $request->get('member_id'))->first();
            if (!$member) {
                return api_error(['member_id' => 'There is no Member against this member Id']);
            }

            $this->memberService->addDetailUpdatedDateTime($member);

            $memberDirectPaymentDetails = $member->directPaymentDetails;

            if (!$memberDirectPaymentDetails) {
                $memberDirectPaymentDetails = new MemberDirectPaymentDetails();
                $memberDirectPaymentDetails->member_id = $member->id;
            }
            $updatedMemberDirectPaymentDetails = $this->memberService->updateMemberDirectPaymentDetails($memberDirectPaymentDetails, $memberdirectPaymentDetailFeild, $memberdirectPaymentDetailFieldValue);
            $updatedMemberDirectPaymentDetails->refresh();
            return api_response($updatedMemberDirectPaymentDetails);

        } else {
            return api_response(null, ['invalid_field' => 'invalid field']);
        }


    }

    public function getMemberPaymentInfo($memberId, Request $request)
    {
        $organization = $request->get(Organization::NAME);
        if (empty($organization)) {
            return api_error(['error' => 'No Current Organization Selected']);
        }

        /**
         * @var $member Member
         */
        $member = $organization->members()->find($memberId);
        if (empty($member)) {
            return api_error(['error' => 'Invalid Member Id']);
        }

        $memberDirectPaymentDetails = $member->directPaymentDetails;
        return api_response($memberDirectPaymentDetails);
    }

    public function getSubscriptionExpiredMembers(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);
        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'subscription_id' => 'required',
            'duration' => 'required|in:last_30_days,last_60_days,last_90_days,due,over_due,active',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        /**
         * @var  $subscription Subscription
         */
        if ($request->get('subscription_id') != 'all') {
            $subscription = $organization->subscriptions()->find($request->get('subscription_id'));

            if (empty($subscription)) {
                return api_error(['subscription_id' => 'Invalid Subscription']);
            }
        }

        if (!empty($subscription)) {
            switch ($request->get('duration')) {
                case "over_due":
                    $members = $subscription->members()->notResigned()->notExpired()->whereBetween('renewal', [Carbon::now()->subDay(29)->toDateTimeString(), Carbon::now()->toDateTimeString()])->get(
                        ['members.id', 'members.subscription_id', 'members.member_id', 'members.first_name', 'members.last_name', 'members.subscription', 'members.renewal']
                    );
                    break;
                case "last_30_days":
                    $members = $subscription->members()->notResigned()->notExpired()->whereBetween('renewal', [Carbon::now()->subDays(59)->toDateTimeString(), Carbon::now()->subDays(30)->toDateTimeString()])->get(
                        ['members.id', 'members.subscription_id', 'members.member_id', 'members.first_name', 'members.last_name', 'members.subscription', 'members.renewal']
                    );
                    break;
                case "last_60_days":
                    $members = $subscription->members()->notResigned()->notExpired()->whereBetween('renewal', [Carbon::now()->subDays(89)->toDateTimeString(), Carbon::now()->subDays(60)->toDateTimeString()])->get(
                        ['members.id', 'members.subscription_id', 'members.member_id', 'members.first_name', 'members.last_name', 'members.subscription', 'members.renewal']
                    );
                    break;
                case "last_90_days":
                    $members = $subscription->members()->notResigned()->notExpired()->where('renewal', '<', Carbon::now()->subDays(90))->get(
                        ['members.id', 'members.subscription_id', 'members.member_id', 'members.first_name', 'members.last_name', 'members.subscription', 'members.renewal', 'members.subscription_id']
                    );
                    break;
                case "due":
                    $members = $subscription->members()->notResigned()->notExpired()->where('due', IStatus::ACTIVE)->get(
                        ['members.id', 'members.subscription_id', 'members.member_id', 'members.first_name', 'members.last_name', 'members.subscription', 'members.renewal', 'members.subscription_id']
                    );
                    break;
                case "active":
                    $members = $subscription->members()->notResigned()->notExpired()->where('status', IStatus::ACTIVE)->get(
                        ['members.id', 'members.subscription_id', 'members.member_id', 'members.first_name', 'members.last_name', 'members.subscription', 'members.renewal', 'members.subscription_id']
                    );
                    break;

                default:
                    return api_error(['duration' => 'invalid Duration.']);

            }
        } else {
            switch ($request->get('duration')) {
                case "over_due":
                    $members = Member::notResigned()->notExpired()
                        ->whereIn('subscription_id', $organization->subscriptions()->pluck('id'))
                        ->whereBetween('renewal', [Carbon::now()->subDay(29)->toDateTimeString(), Carbon::now()->toDateTimeString()])->get(
                            ['members.id', 'members.subscription_id', 'members.member_id', 'members.first_name', 'members.last_name', 'members.subscription', 'members.renewal']
                        );
                    break;
                case "last_30_days":
                    $members = Member::notResigned()->notExpired()
                        ->whereIn('subscription_id', $organization->subscriptions()->pluck('id'))
                        ->whereBetween('renewal', [Carbon::now()->subDays(59)->toDateTimeString(), Carbon::now()->subDays(30)->toDateTimeString()])->get(
                            ['members.id', 'members.subscription_id', 'members.member_id', 'members.first_name', 'members.last_name', 'members.subscription', 'members.renewal']
                        );
                    break;
                case "last_60_days":
                    $members = Member::notResigned()->notExpired()
                        ->whereIn('subscription_id', $organization->subscriptions()->pluck('id'))->whereBetween('renewal', [Carbon::now()->subDays(89)->toDateTimeString(), Carbon::now()->subDays(60)->toDateTimeString()])->get(
                            ['members.id', 'members.subscription_id', 'members.member_id', 'members.first_name', 'members.last_name', 'members.subscription', 'members.renewal']
                        );
                    break;
                case "last_90_days":
                    $members = Member::notResigned()->notExpired()
                        ->whereIn('subscription_id', $organization->subscriptions()->pluck('id'))->where('renewal', '<', Carbon::now()->subDays(90))->get(
                            ['members.id', 'members.subscription_id', 'members.member_id', 'members.first_name', 'members.last_name', 'members.subscription', 'members.renewal', 'members.subscription_id']
                        );
                    break;
                case "due":
                    $members = Member::notResigned()->notExpired()
                        ->whereIn('subscription_id', $organization->subscriptions()->pluck('id'))->where('due', IStatus::ACTIVE)->get(
                            ['members.id', 'members.subscription_id', 'members.member_id', 'members.first_name', 'members.last_name', 'members.subscription', 'members.renewal', 'members.subscription_id']
                        );
                    break;
                case "active":
                    $members = Member::notResigned()->notExpired()
                        ->whereIn('subscription_id', $organization->subscriptions()->pluck('id'))->where('status', IStatus::ACTIVE)->get(
                            ['members.id', 'members.subscription_id', 'members.member_id', 'members.first_name', 'members.last_name', 'members.subscription', 'members.renewal', 'members.subscription_id']
                        );
                    break;

                default:
                    return api_error(['duration' => 'invalid Duration.']);

            }
        }

        return api_response($members);
    }

    public function getMemberVouchers(Request $request)
    {

        /* @var $member Member */
        $member = $request->get('member');

        if (!$member) {
            return api_error(['error' => 'Member not found'], IResponseCode::USER_NOT_LOGGED_IN);
        }

        $voucherRepository = new VoucherRepository();
        $vouchers = $voucherRepository->getMemberVoucherCards($member->email);

        return api_response($vouchers);

    }

    /**
     * Assign Coffee Card to the member by scanning the coffee card code.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ApiException
     */
    public function assignCoffeeCard(Request $request)
    {
        /* @var $member Member */
        $member = $request->get('member');

        if (!$member) {
            return api_error(['error' => 'Member not found'], IResponseCode::USER_NOT_LOGGED_IN);
        }

        $validationRules = [
            'coffee_card_code' => 'required|exists:coffee_cards,card_code',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        /** @var CoffeeCard $coffeeCard */
        $coffeeCard = CoffeeCard::whereCardCode($request->get('coffee_card_code'))->first();

        $coffeeCardAssigned = $member->memberCoffeeCard()->where('coffee_card_id', $coffeeCard->id)->count();

        // if the card with the same coffee card code is there for this member, do nothing and send empty response back.
        if ($coffeeCardAssigned) {
            return api_response([]);
        }

        /** @var CoffeeCardRepository $coffeeCardRepository */
        $coffeeCardRepository = new CoffeeCardRepository();

        /** @var MemberCoffeeCard $memberCoffeeCard */
        $memberCoffeeCard = $coffeeCardRepository->createMemberCoffeeCard($coffeeCard, $member); // create member coffee card with 0 stamp earned and 0 stamp balance if that is not already able for this member.

        return api_response($memberCoffeeCard);
    }

    /**
     * This api will returned all the active rewards for the member. (Memberme mobile api function)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRewardList(Request $request)
    {
        /* @var $member Member */
        $member = $request->get('member');

        if (!$member) {
            return api_error(['error' => 'Member not found'], IResponseCode::USER_NOT_LOGGED_IN);
        }
        /** @var CoffeeCardRepository $coffeeCardRepository */
        $coffeeCardRepository = new CoffeeCardRepository();

        /** @var [] $rewards */
        $rewards = $coffeeCardRepository->getMemberRewards($member);

        return api_response($rewards);
    }

    public function getMemberCoffeeCardList(Request $request)
    {
        /* @var $member Member */
        $member = $request->get('member');

        if (!$member) {
            return api_error(['error' => 'Member not found'], IResponseCode::USER_NOT_LOGGED_IN);
        }
        /** @var CoffeeCardRepository $coffeeCardRepository */
        $coffeeCardRepository = new CoffeeCardRepository();

        $memberCoffeeCardList = $coffeeCardRepository->getMemberCoffeeCardList($member);

        return api_response($memberCoffeeCardList);
    }

    /**
     * This will get the id of member coffee card and return the logs of that membercard related to the logged in member.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCoffeeCardLogs(Request $request)
    {
        /* @var $member Member */
        $member = $request->get('member');

        if (!$member) {
            return api_error(['error' => 'Member not found'], IResponseCode::USER_NOT_LOGGED_IN);
        }


        $validationRules = [
            'member_coffee_card_id' => 'required|exists:member_coffee_cards,id',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }
        /** @var MemberCoffeeCard $memberCoffeeCard */
        $memberCoffeeCard = $member->memberCoffeeCard()->where('member_coffee_cards.id', $request->get('member_coffee_card_id'))->first();
        if (empty($memberCoffeeCard)) {
            return api_error(['error' => 'Member Coffee Card Not Found']);
        }

        /** @var CoffeeCardRepository $coffeeCardRepository */
        $coffeeCardRepository = new CoffeeCardRepository();

        $memberCoffeeCardLogs = $coffeeCardRepository->getMemberCoffeeCardLogs($memberCoffeeCard);

        return api_response($memberCoffeeCardLogs);
    }

    public function reGenerateMemberCard(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'member_id' => 'required|exists:members,id'
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        $member = $organization->members()->where('members.id', $request->get('member_id'))->first();
        if (!$member) {
            return api_error(['error' => 'Invalid member id']);
        }


        $member = $this->memberService->reGenerateMemberCard($member);
        return api_response($member);
    }

    /**
     * Verify Member id from add member popup.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyMemberId(Request $request, $id)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        if ($this->memberService->verifyMemberId($organization, $id)) {
            return api_response([]);
        }
        return api_error(['error' => 'Member number already taken.']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNextMemberNumber(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $organizationDetails = $organization->details;
        if (!empty($organizationDetails)) {
            return api_response($organizationDetails->next_member);
        }

        return api_response(null);
    }

    public function delete(Request $request, $id)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        //todo Delete Member, remove all the relations, remove it from the sms Lists.
        return api_response(null, null, 'Member Removed Successfully.');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ApiException
     */
    public function getMemberCardNames(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $this->memberService->getPosClientCardName($organization);
        $clientCardNames = (new RecordRepository(new Record()))->getAllPosClientCardName($organization->id);
        return api_response($clientCardNames,null,'Member Card Names Fetched Successfully');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMemberViewLogs(Request $request, $memberId)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);
        return api_response($this->memberService->getMemberViewLog($organization, $memberId));
    }

    public function getMemberChangeLogs(Request $request, $id)
    {
        /**
         * @var $organization Organization
         */
        $organization = $request->get(Organization::NAME);

        $member = $organization->members()->where('members.id' , $id)->first();
        $result = [];
        if($member){
            $result = $this->memberService->getMemberChangeLogs($member, $request->all());
        }
        return api_response($result);
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addViewLog(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);
        return api_response($this->memberService->addMemberViewLog($organization, $request->all()));
    }

    public function deleteMemberCoffeeCard(Request $request)
    {
        /* @var $member Member */
        $member = $request->get('member');

        if (!$member) {
            return api_error(['error' => 'Member not found'], IResponseCode::USER_NOT_LOGGED_IN);
        }

        $validationRules = [
            'member_coffee_card_id' => 'required|exists:member_coffee_cards,id',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        /** @var MemberCoffeeCard $memberCoffeeCard */
        $memberCoffeeCard = $member->memberCoffeeCard()->where('member_coffee_cards.id',$request->get('member_coffee_card_id'))->first();
        if(empty($memberCoffeeCard)){
            return api_error(['error' => 'Invalid Coffee Card Code']);
        }

        $this->memberService->removeMemberCoffeeCard($memberCoffeeCard);

        return api_response([],[],'Member stamp card removed successfully');
    }

}
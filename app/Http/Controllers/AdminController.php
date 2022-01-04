<?php

namespace App\Http\Controllers;

use App\Address;
use App\base\AddressType;
use App\base\IRecordType;
use App\base\IResponseCode;
use App\base\IStatus;
use App\Country;
use App\Exceptions\ApiException;
use App\Group;
use App\Helpers\ApiHelper;
use App\Helpers\DropdownHelper;
use App\Member;
use App\MemberOther;
use App\Organization;
use App\Record;
use App\repositories\GroupRepository;
use App\repositories\MemberRepository;
use App\repositories\OrganizationRepository;
use App\repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /* @var $orgRepo OrganizationRepository */
    public $orgRepo;

    /* @var $userRepo UserRepository */
    public $userRepo;

    /** @var  $memberRepo MemberRepository */
    public $memberRepo;

    public function __construct(UserRepository $userRepository, OrganizationRepository $organizationRepository, MemberRepository $memberRepository)
    {
        $this->userRepo = $userRepository;
        $this->orgRepo = $organizationRepository;
        $this->memberRepo = $memberRepository;
    }

    /**
     * @api {get} /admin/get-all-organizations [[val-04-01]] Get All Organizations
     * @apiVersion 0.1.0
     * @apiName [[val-04-01]] Get All Organizations
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Accept": "application/json",
     *       "Authorization: "Bearer {access_token}"
     *     }
     * @apiGroup Admin
     * @apiPermission Secured (Super Admin, Administrator)
     * @apiDescription Get All Organizations under this website.
     *
     * @apiSuccessExample {json} Success-Example:
     *  {
     * "_metadata": {
     * "status": "passed"
     * },
     * "pending_org": null,
     * "data": [
     * {
     * "id": 15552,
     * "name": "Random Founders",
     * "current": 1,
     * "user_id": 2,
     * "data": {
     * "logo": "http://api.memberme.me/storage/organization/logo/4685bfe36bb4436372d99df45ce00def.png"
     * },
     * "status": 1,
     * "created_at": "2017-11-11 18:28:52",
     * "updated_at": "2017-11-13 08:06:47",
     * "details": {
     * "id": 1,
     * "organization_id": 15552,
     * "bio": null,
     * "contact_name": "feci",
     * "contact_email": "test@memberme.me",
     * "contact_phone": "22222",
     * "office_phone": "223322233",
     * "industry": 1,
     * "account_no": "15552",
     * "logo": null,
     * "cover": null,
     * "physical_address_id": "1",
     * "postal_address_id": "4",
     * "gst_number": null,
     * "starting_member": "1",
     * "starting_receipt": "1",
     * "next_member": "1",
     * "data": null,
     * "created_at": "2017-11-11 18:48:50",
     * "updated_at": "2017-11-14 18:24:40"
     * }
     * },
     * {
     * "id": 15554,
     * "name": "Test Organization 2",
     * "current": null,
     * "user_id": 2,
     * "data": null,
     * "status": 2,
     * "created_at": "2017-11-11 18:28:52",
     * "updated_at": "2017-11-11 18:35:34",
     * "details": null
     * },
     * {
     * "id": 15558,
     * "name": "Org 3",
     * "current": 1,
     * "user_id": 9,
     * "data": null,
     * "status": 2,
     * "created_at": "2017-11-13 21:23:08",
     * "updated_at": "2017-11-13 21:23:08",
     * "details": null
     * },
     * {
     * "id": 15561,
     * "name": "Org6",
     * "current": 1,
     * "user_id": 12,
     * "data": null,
     * "status": 2,
     * "created_at": "2017-11-14 17:55:27",
     * "updated_at": "2017-11-14 17:55:27",
     * "details": null
     * }
     * ],
     * "message": null
     * }
     *
     * @apiErrorExample {json} Error-Response:
     * {
     * "_metadata": {
     * "status": "failed"
     * },
     * "errors": {
     * "permissions": "This user have not permissions for this task"
     * }
     * }
     */
    public function getAllOrg()
    {
        $data = $this->orgRepo->all();
        return response(ApiHelper::apiResponse($data, null), 200);
    }

    /**
     * @api {get} /admin/is-admin [[val-04-02]] Is Admin
     * @apiVersion 0.1.0
     * @apiName [[val-04-01]] is-admin
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Accept": "application/json",
     *       "Authorization: "Bearer {access_token}"
     *     }
     * @apiGroup Admin
     * @apiPermission Secured (Super Admin, Administrator)
     * @apiDescription Send access token and check if logged in user is a super admin or administrator
     *
     * @apiSuccessExample {json} Success-Example:
     *  {
     * "_metadata": {
     * "status": "passed"
     * },
     * "pending_org": null,
     * "data": {
     * "is_admin": 1
     * },
     * "message": "This user is super admin"
     * }
     *
     * @apiErrorExample {json} Error-Response:
     * {
     * "_metadata": {
     * "status": "failed"
     * },
     * "errors": {
     * "permissions": "This user have not permissions for this task"
     * }
     * }
     */
    public function isAdmin()
    {
        return response(ApiHelper::apiResponse(['is_admin' => IStatus::ACTIVE], null, 'This user is super admin'));
    }


    public function importMembers(Request $request)
    {
        ini_set('upload_max_filesize', '50M');
        ini_set('post_max_size', '55M');
        set_time_limit(0);

        /** @var $organization Organization */

        $validationRules = [
//            'file_to_import' => 'required',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

//        $file = $_FILES['file_to_import'];
        $importFileTemp = $request->file('file_to_import'); //temporary file to import the members.\

        $filePath = $importFileTemp->getPath() . '/' . $importFileTemp->getFilename();
        $membersInCsv = array_map('str_getcsv', file($filePath));
        echo "<pre>";
        $isFirstLine = true;

        //if the file is empty then report with the error.
        if (empty($membersInCsv[1])) {
            return api_error(['error' => 'Attached file is empty or invalid formatted.']);
        }

        //looping through all the files from csv.
        for ($i = 1; $i < count($membersInCsv); $i++) {
            $memberToImport = $membersInCsv[$i];

            //region Invalid Organization Check
            $organization = Organization::find($memberToImport[0]);
            if (empty($organization)) {

                if ($isFirstLine) {
                    write_import_logs($membersInCsv[1][0], $membersInCsv[0], $isFirstLine);
                    $isFirstLine = false;
                }
                $fileUrl = write_import_logs($membersInCsv[1][0], $memberToImport, $isFirstLine, \App\Member::IMPORT_ERROR_MESSAGE['INVALID_ORGANIZATION']);
                continue;
            }
            //endregion

            //region Invalid Member Number Check
            $sameMemberNumberExists = $organization->members()->where(['member_id' => $memberToImport['1']])->first();
            if (!empty($sameMemberNumberExists) && !empty($memberToImport['1'])) {
                if ($isFirstLine) {
                    write_import_logs($organization->name, $membersInCsv[0], $isFirstLine);
                    $isFirstLine = false;
                }
                $fileUrl = write_import_logs($organization->name, $memberToImport, $isFirstLine, \App\Member::IMPORT_ERROR_MESSAGE['MEMBER_WITH_SAME_ID_EXISTS']);
                continue;
            }
            //endregion

            $isSubsciptionExpires = false;
            //region Invalid subscription Check
            $subscription = $organization->subscriptions()->where(['title' => $memberToImport[50]])->first();
            if (empty($subscription) && $memberToImport[50] != '') {
                if ($isFirstLine) {
                    write_import_logs($organization->name, $membersInCsv[0], $isFirstLine);
                    $isFirstLine = false;
                }
                $fileUrl = write_import_logs($organization->name, $memberToImport, $isFirstLine, \App\Member::IMPORT_ERROR_MESSAGE['INVALID_SUBSCRIPTION']);
                continue;
            }

            if (!empty($subscription)) {
                $expires = $subscription->expires;
                $isSubsciptionExpires = ($expires == IStatus::ACTIVE) ? true : false;
            }
            //endregion

            //region Member with same email Check
            $sameMemberEmailExists = $organization->members()->where([
                'members.email' => $memberToImport[5]
            ])
                ->first();

            /* if(!filter_var($memberToImport[5], FILTER_VALIDATE_EMAIL)){
                 if($isFirstLine){
                     write_import_logs($organization->name ,$membersInCsv[0],$isFirstLine);
                     $isFirstLine = false;
                 }
                 $fileUrl = write_import_logs($organization->name,$memberToImport,$isFirstLine,\App\Member::IMPORT_ERROR_MESSAGE['INVALID_EMAIL']);
                 continue;
             }*/
            if (!empty($sameMemberEmailExists) && !empty($memberToImport[5])) {
                if ($isFirstLine) {
                    write_import_logs($organization->name, $membersInCsv[0], $isFirstLine);
                    $isFirstLine = false;
                }
                $fileUrl = write_import_logs($organization->name, $memberToImport, $isFirstLine, \App\Member::IMPORT_ERROR_MESSAGE['MEMBER_WITH_SAME_EMAIL_EXISTS']);
                continue;
            }
            //endregion

            //region Proposer, Secondary and parent member check
//            $proposerMember = $organization->members()->where([
//                'members.member_id' => $memberToImport[35]
//            ])
//                ->first();
//            if (empty($proposerMember) && !empty($memberToImport[35])) {
//
//                if ($isFirstLine) {
//                    write_import_logs($organization->name, $membersInCsv[0], $isFirstLine);
//                    $isFirstLine = false;
//                }
//                $fileUrl = write_import_logs($organization->name, $memberToImport, $isFirstLine, \App\Member::IMPORT_ERROR_MESSAGE['INVALID_PROPOSER_ID']);
//                continue;
//            }
//
//
//            $secondaryMember = $organization->members()->where([
//                'members.member_id' => $memberToImport[37]
//            ])
//                ->first();
//            if (empty($secondaryMember) && !empty($memberToImport[37])) {
//
//                if ($isFirstLine) {
//                    write_import_logs($organization->name, $membersInCsv[0], $isFirstLine);
//                    $isFirstLine = false;
//                }
//                $fileUrl = write_import_logs($organization->name, $memberToImport, $isFirstLine, \App\Member::IMPORT_ERROR_MESSAGE['INVALID_SECONDARY_NUMBER']);
//
//                continue;
//            }
//
//
//            $parentMember = $organization->members()->where([
//                'members.member_id' => $memberToImport[32]
//            ])
//                ->first();
//            if (empty($parentMember) && !empty($memberToImport[32])) {
//
//                if ($isFirstLine) {
//                    write_import_logs($organization->name, $membersInCsv[0], $isFirstLine);
//                    $isFirstLine = false;
//                }
//                $fileUrl = write_import_logs($organization->name, $memberToImport, $isFirstLine, \App\Member::IMPORT_ERROR_MESSAGE['INVALID_PARENT_NUMBER']);
//
//                continue;
//            }

            $physicalCountry = Country::where('name', array_get($memberToImport, 13))->select('id')->first();

            if (empty($physicalCountry) && strlen(array_get($memberToImport, 13)) > 2) {
                if ($isFirstLine) {
                    write_import_logs($organization->name, $membersInCsv[0], $isFirstLine);
                    $isFirstLine = false;
                }
                $fileUrl = write_import_logs($organization->name, $memberToImport, $isFirstLine, \App\Member::IMPORT_ERROR_MESSAGE['INVALID_PHYSICAL_COUNTRY']);

                continue;
            }

            $postalCountry = Country::where('name', array_get($memberToImport, 19))->select('id')->first();
            if (empty($postalCountry) && strlen(array_get($memberToImport, 19)) > 2) {
                if ($isFirstLine) {
                    write_import_logs($organization->name, $membersInCsv[0], $isFirstLine);
                    $isFirstLine = false;
                }
                $fileUrl = write_import_logs($organization->name, $memberToImport, $isFirstLine, \App\Member::IMPORT_ERROR_MESSAGE['INVALID_POSTAL_COUNTRY']);

                continue;
            }

            //endregion

            $prepareData = [
                'organization_id' => array_get($memberToImport, 0),
                'first_name' => array_get($memberToImport, 2),
                'middle_name' => array_get($memberToImport, 3),
                'last_name' => array_get($memberToImport, 4),
                'email' => array_get($memberToImport, 5),
                'title' => array_get($memberToImport, 6),
                'facebook_id' => array_get($memberToImport, 7),
                'known_as' => array_get($memberToImport, 8),
                'gender' => array_get($memberToImport, 9),
                'phone' => array_get($memberToImport, 10),
                'date_of_birth' => array_get($memberToImport, 11),
                'contact_no' => array_get($memberToImport, 12),
                'subscription' => ($subscription) ? $subscription->id : null,
//                'member_id'             => array_get($memberToImport,1),
                'status' => DropdownHelper::getMemberStatusIdByName(array_get($memberToImport, 51)),
                'parent_code' => (!empty($parentMember))? array_get($parentMember, 'id'):null,
            ];

            if (filter_var($memberToImport[5], FILTER_VALIDATE_EMAIL)) {
                $prepareData['email'] = array_get($memberToImport, 5);
            } else {
                $prepareData['email'] = null;
            }

            $date = array_get($memberToImport, 11);
            $explodedDate = explode("/", $date);
            if (count($explodedDate) == 3 && checkdate($explodedDate[1], $explodedDate[0], $explodedDate[2])) {
                $prepareData['date_of_birth'] = array_get($memberToImport, 11);
            } else {
                $prepareData['date_of_birth'] = null;
            }


            $memberRepository = new MemberRepository();
            /* @var $importedMember Member */
            $importedMember = $memberRepository->addMember($prepareData, false);
            if (!empty($importedMember)) {
                if (!empty($subscription)) {
                    $importedMember->subscription_id = array_get($subscription, 'id');
                }

                if (!$isSubsciptionExpires) {
                    if (array_get($memberToImport, 63) != '') {
                        $importedMember->financial = array_get($memberToImport, 63, IStatus::ACTIVE);
                    } else {
                        $importedMember->financial = IStatus::ACTIVE;
                    }
                } else {
                    if (array_get($memberToImport, 63) != '') {
                        $importedMember->financial = array_get($memberToImport, 63, IStatus::INACTIVE);
                    } else {
                        $importedMember->financial = IStatus::INACTIVE;
                    }
                }

                $importedMember->due = array_get($memberToImport, 64);
                $importedMember->member_id = array_get($memberToImport, 1);
                if (empty($importedMember->status)) {
                    $importedMember->status = IStatus::PENDING_NEW;
                }
                $importedMember->save();

                //region Saving Physical Address
                if (!empty(array_get($memberToImport, 13))) {
                    //todo add check on the country
                    $preparedPhysicalAddressData = [
                        'country_id' => array_get($physicalCountry, 'id'),
                        'address1' => array_get($memberToImport, 14),
                        'suburb' => array_get($memberToImport, 15),
                        'postal_code' => array_get($memberToImport, 16),
                        'city' => array_get($memberToImport, 17),
                        'region' => array_get($memberToImport, 18),
                        'item_type_id' => AddressType::MEMBER,
                        'address_type_id' => AddressType::PHYSICAL_ADDRESS,
                        'item_id' => array_get($importedMember, 'id'),
                        'status_id' => IStatus::ACTIVE,
                        'longitude' => null,
                        'latitude' => null,
                        'address2' => null,
                    ];

                    $physicalAddress = Address::create($preparedPhysicalAddressData);
                    $importedMember->physical_address_id = array_get($physicalAddress, 'id');
                }
                //endregion

                //region Saving Postal Address
                if (!empty(array_get($memberToImport, 19))) {
                    $preparedPostalAddressData = [
                        'country_id' => array_get($postalCountry, 'id'),
                        'address1' => array_get($memberToImport, 20),
                        'suburb' => array_get($memberToImport, 21),
                        'postal_code' => array_get($memberToImport, 22),
                        'city' => array_get($memberToImport, 23),
                        'region' => array_get($memberToImport, 24),
                        'item_type_id' => AddressType::MEMBER,
                        'address_type_id' => AddressType::PHYSICAL_ADDRESS,
                        'item_id' => array_get($importedMember, 'id'),
                        'status_id' => IStatus::ACTIVE,
                        'longitude' => null,
                        'latitude' => null,
                        'address2' => null,
                    ];

                    $postalAddress = Address::create($preparedPostalAddressData);
                    $importedMember->postal_address_id = array_get($postalAddress, 'id');
                }
                //endregion
                $importedMember->save();

                //region Saving Member Other Details
                $memberOther = MemberOther::where(['member_id' => $importedMember->id])->first();
                if (empty($memberOther)) {
                    $memberOther = new MemberOther();
                }
                $memberOther->transferred_from = array_get($memberToImport, '26');
                $memberOther->occupation = array_get($memberToImport, '28');
                $memberOther->parent_code = array_get($memberToImport, '32');


                //region Handling Default Values of Member Other
                if (array_get($memberToImport, '31', null) != '') {
                    $memberOther->mailing_list = array_get($memberToImport, '31', IStatus::ACTIVE);
                } else {
                    $memberOther->mailing_list = IStatus::ACTIVE;
                }

                if (array_get($memberToImport, '33', null) != '') {
                    $memberOther->approved = array_get($memberToImport, '33', IStatus::ACTIVE);
                } else {
                    $memberOther->approved = IStatus::ACTIVE;
                }

                if (array_get($memberToImport, '25', null) != '') {
                    $memberOther->receive_email = array_get($memberToImport, '25', IStatus::ACTIVE);
                } else {
                    $memberOther->receive_email = IStatus::ACTIVE;
                }

                if (array_get($memberToImport, '27', null) != '') {
                    $memberOther->receive_sms = array_get($memberToImport, '27', IStatus::ACTIVE);
                } else {
                    $memberOther->receive_sms = IStatus::ACTIVE;
                }

                if (array_get($memberToImport, '29', null) != '') {
                    $memberOther->newsletter = array_get($memberToImport, '29', IStatus::ACTIVE);
                } else {
                    $memberOther->newsletter = IStatus::ACTIVE;
                }

                if (array_get($memberToImport, '39', null) != '') {
                    $memberOther->rsa = array_get($memberToImport, '39', IStatus::ACTIVE);
                } else {
                    $memberOther->rsa = IStatus::INACTIVE;
                }

                if (array_get($memberToImport, '45', null) != '') {
                    $memberOther->earn_points = array_get($memberToImport, '45', IStatus::ACTIVE);
                } else {
                    $memberOther->earn_points = IStatus::ACTIVE;
                }

                if (array_get($memberToImport, '43', null) != '') {
                    $memberOther->price_level = array_get($memberToImport, '43', IStatus::ACTIVE);
                } else {
                    $memberOther->price_level = IStatus::ACTIVE;
                }

                if (array_get($memberToImport, '30', null) != '') {
                    $memberOther->deceased = array_get($memberToImport, '30', IStatus::INACTIVE);
                } else {
                    $memberOther->deceased = IStatus::INACTIVE;
                }

                if (array_get($memberToImport, '34', null) != '') {
                    $memberOther->senior = array_get($memberToImport, '34', IStatus::INACTIVE);
                } else {
                    $memberOther->senior = IStatus::INACTIVE;
                }
                //endregion

                if (array_get($memberToImport, '35', null) != '') {
                    $memberOther->proposer_member_id = array_get($memberToImport, '35', null);
                }

                if (array_get($memberToImport, '37', null) != '') {
                    $memberOther->secondary_member_id = array_get($memberToImport, '37', null);
                }

                if (array_get($memberToImport, '45', null) != '') {
                    $memberOther->earn_points = array_get($memberToImport, '45', null);
                }

                $memberOther->rsa_type = array_get($memberToImport, '40');
                $memberOther->company = array_get($memberToImport, '41');
                $memberOther->served = array_get($memberToImport, '42');
                $memberOther->points = array_get($memberToImport, '44');
                $memberOther->credit_limit = array_get($memberToImport, '46');
                $memberOther->discount = array_get($memberToImport, '47');
                $memberOther->swipe_card = array_get($memberToImport, '48');
                $memberOther->prox_card = array_get($memberToImport, '49');
                $memberOther->member_id = array_get($importedMember, 'id');
                $memberOther->save();
                //endregion

                $groupNames = [];
                $activityNames = [];
                if (!empty(array_get($memberToImport, '52'))) {
                    $groupNames[] = array_get($memberToImport, '52');
                }
                if (!empty(array_get($memberToImport, '53'))) {
                    $groupNames[] = array_get($memberToImport, '53');
                }
                if (!empty(array_get($memberToImport, '54'))) {
                    $groupNames[] = array_get($memberToImport, '54');
                }
                if (!empty(array_get($memberToImport, '55'))) {
                    $groupNames[] = array_get($memberToImport, '55');
                }
                if (!empty(array_get($memberToImport, '56'))) {
                    $groupNames[] = array_get($memberToImport, '56');
                }
                if (!empty(array_get($memberToImport, '57'))) {
                    $activityNames[] = array_get($memberToImport, '57');
                }
                if (!empty(array_get($memberToImport, '58'))) {
                    $activityNames[] = array_get($memberToImport, '58');
                }
                if (!empty(array_get($memberToImport, '59'))) {
                    $activityNames[] = array_get($memberToImport, '59');
                }
                if (!empty(array_get($memberToImport, '60'))) {
                    $activityNames[] = array_get($memberToImport, '60');
                }
                if (!empty(array_get($memberToImport, '61'))) {
                    $activityNames[] = array_get($memberToImport, '61');
                }

                $groupRepo = new GroupRepository();
                foreach ($groupNames as $groupName) {
                    $group = $groupRepo->findOrCreate($organization, $groupName, Group::TYPE['ADJUNCT']);
                    $importedMember->groups()->save($group);
                }

                //member renewal date if exist.
                $date = array_get($memberToImport, 62);
                $explodedDate = explode("/", $date);
                if (count($explodedDate) == 3 && checkdate($explodedDate[1], $explodedDate[0], $explodedDate[2])) {
                    $dashDate = str_replace('/', '-', $date);
                    $dateToSet = date('Y-m-d h:s:i', strtotime($dashDate));
                    $importedMember->renewal = $dateToSet;
                    $importedMember->save();
                }

                //member joining date
                $date = array_get($memberToImport, 65);
                $explodedDate = explode("/", $date);
                if (count($explodedDate) == 3 && checkdate($explodedDate[1], $explodedDate[0], $explodedDate[2])) {
                    $dashDate = str_replace('/', '-', $date);
                    $dateToSet = date('Y-m-d h:s:i', strtotime($dashDate));
//                    $importedMember->created_at =  $dateToSet;
                    $importedMember->joining_date = $dateToSet;
                    $importedMember->save();
                }

                //adding activities.
                foreach ($activityNames as $activityName) {
                    $group = $groupRepo->findOrCreate($organization, $activityName, Group::TYPE['ACTIVITY']);
                    $importedMember->groups()->save($group);
                }


                try {
                    $importedMember->status = DropdownHelper::getMemberStatusIdByName(array_get($memberToImport, 51));
                    $importedMember->is_imported = true;
                    $importedMember->save();
                } catch (\Exception $exception) {
                    \Log::info('Issue in importing status of member #: ' . array_get($importFileTemp, 'id') . ' Status: ' . array_get($memberToImport, 51));
                }

            }
        }

        // If a member is having empty joining date, setting joining date by default.
        try {
            Member::where([
                'joining_date' => '00-00-0000 00:00:00',
            ])->update(['joining_date' => Carbon::now()]);
        } catch (\Exception $exception) {
            \Log::info('issue with updating date from 00-00-0000 to now' . date('d-m-Y h:s:i'));
        }
        if (!empty($fileUrl)) {
            return api_response($fileUrl, null, 'Member import finished with a log file.');
        } else {
            return api_response(null, null, 'Member Imported Successfully');

        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiException
     */
    public function pushMembersToPos(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        //region fetching member Client Card Names

        $memberRepository = new MemberRepository();

        $memberRepository->getPosClientCardName($organization);

        $memberCardNamesCount = Record::where([
            'record_type_id' => IRecordType::POS_CLIENT_CARD_NAME,
            'organization_id' => $organization->id
        ])->whereIn('name' , Member::CARD_NAME)
            ->count();
        if($memberCardNamesCount < 3 ){
            throw new ApiException(null,null,'Card Names are not set or fetched properly', IResponseCode::NOT_FOUND);
        }
        //endregion

        $organization->members()->whereNull('members.untill_id')->chunk(20, function ($members) use ($organization) {
            $this->memberRepo->pushMembersToPos($organization, $members);
        });
        return api_response(null, null, 'Member have been pushed to the POS');
    }
}

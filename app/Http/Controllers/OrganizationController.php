<?php

namespace App\Http\Controllers;

use App\base\IResponseCode;
use App\base\IStatus;
use App\base\IUserType;
use App\base\AddressType;
use App\EmailTemplate;
use App\Exceptions\ApiException;
use App\Group;
use App\Helpers\ApiHelper;
use App\Http\Requests\SaveOrganizationDetails;
use App\Member;
use App\MemberNotification;
use App\Organization;
use App\OrganizationDetail;
use App\OrganizationOption;
use App\OrganizationSetting;
use App\OrganizationSmsSetting;
use App\repositories\MemberRepository;
use App\repositories\OrganizationRepository;
use App\repositories\RecordRepository;
use App\repositories\UserRepository;
use App\SendgridSetting;
use App\SmsList;
use App\SmsListMember;
use App\SmsListMembers;
use App\User;
use App\Address;
use Carbon\Carbon;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\UnauthorizedException;

class OrganizationController extends Controller
{
    /* @var $orgService OrganizationRepository */
    public $orgService;

    public function __construct()
    {
        $this->orgService = new OrganizationRepository();
    }

    /**
     *
     * Get Details of an organization against a user with organization_id
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDetails($id)
    {
        $org = Organization::where('user_id', Auth::id())->whereId($id)->first();
        if (empty($org)) {
            throw new UnauthorizedException("You are not Authorized for this Action");
        }
        return view('org.get_details', [
            'organization' => $org,
        ]);
    }

    /**
     *
     * @param SaveOrganizationDetails $request
     * @return mixed
     */
    public function saveDetails(SaveOrganizationDetails $request)
    {
        $this->orgService->saveDetails($request);
        return redirect(route("home"));
    }

    /**
     * @param Request $request
     * @return array
     * @api {post} /organization [[val-03-01]] Organization Details Gathering
     * @apiVersion 0.1.0
     * @apiName [[val-03-01]] Organization Details Gathering
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Accept": "application/json",
     *       "Authorization: "Bearer {access_token}"
     *     }
     * @apiParam {number} organization_id Mandatory Organization Id
     * @apiParam {string} organization_id Mandatory Organization name
     * @apiParam {string} contact_name Mandatory Name of contact Person
     * @apiParam {string} contact_phone Mandatory Phone of contact Person
     * @apiParam {string} contact_phone Mandatory Phone of contact Person
     * @apiParam {number} industry Industry Mandatory Type of Organization
     * @apiParam {number} physical_country Mandatory Physical Country_id of Organization
     * @apiParam {string} physical_first_address Optional first address line of Organization
     * @apiParam {string} physical_suburb Optional physical_suburb of Organization
     * @apiParam {string} physical_suburb Optional physical_suburb of Organization
     * @apiParam {string} physical_city Mandatory Physical City of Organization
     * @apiParam {string} physical_region Mandatory physical_region of Organization
     * @apiParam {string} physical_postal_code Mandatory physical_postal_code of Organization
     * @apiParam {string} physical_latitude Mandatory physical_latitude of Organization
     * @apiParam {string} physical_longitude Mandatory physical_longitude of Organization
     * @apiParam {number} next_member Mandatory next_member of Organization
     * @apiParam {number} starting_member Mandatory starting_member of Organization
     * @apiParam {number} gst Optional gst of Organization
     *
     * @apiParam {number} postal_country Mandatory postal Country_id of Organization
     * @apiParam {string} postal_first_address Optional first address line of Organization
     * @apiParam {string} postal_suburb Optional postal_suburb of Organization
     * @apiParam {string} postal_suburb Optional postal_suburb of Organization
     * @apiParam {string} postal_city Mandatory postal City of Organization
     * @apiParam {string} postal_region Mandatory postal_region of Organization
     * @apiParam {string} postal_postal_code Mandatory postal_postal_code of Organization
     *
     * @apiGroup Organization
     * @apiPermission Secured (Super Admin, Administrator)
     * @apiDescription Send details to save against organization_id
     *
     * @apiSuccessExample {json} Success-Example:
     * {
     * "_metadata": {
     * "status": "passed"
     * },
     * "pending_org": {
     * "id": 15554,
     * "name": "Test Organization 2",
     * "current": null,
     * "user_id": 2,
     * "data": null,
     * "status": 2,
     * "created_at": "2017-11-11 18:28:52",
     * "updated_at": "2017-11-11 18:35:34"
     * },
     * "message": "data successfully updated",
     * "data": {
     * "user": {
     * "id": 2,
     * "first_name": "Faisal",
     * "last_name": "Arif",
     * "middle_name": null,
     * "email": "test@memberme.me",
     * "contact_no": "0343543524",
     * "address_id": null,
     * "bio": null,
     * "user_type_id": 3,
     * "status_id": 1,
     * "api_token": "mYvhOk4YERkiSOvXB7rppnvrFzx8yfeoc0KE1eY0jZRH0UImU4iONIkTzqMf",
     * "verify": 1,
     * "notes": null,
     * "activate": 1,
     * "data": null,
     * "created_at": "2017-11-11 18:35:33",
     * "updated_at": "2017-11-12 20:09:06",
     * "role": {
     * "name": "Manager",
     * "id": 3
     * }
     * },
     * "organization": {
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
     * "owner": {
     * "id": 2,
     * "first_name": "Faisal",
     * "last_name": "Arif",
     * "middle_name": null,
     * "email": "test@memberme.me",
     * "contact_no": "0343543524",
     * "address_id": null,
     * "bio": null,
     * "user_type_id": 3,
     * "status_id": 1,
     * "api_token": "mYvhOk4YERkiSOvXB7rppnvrFzx8yfeoc0KE1eY0jZRH0UImU4iONIkTzqMf",
     * "verify": 1,
     * "notes": null,
     * "activate": 1,
     * "data": null,
     * "created_at": "2017-11-11 18:35:33",
     * "updated_at": "2017-11-12 20:09:06"
     * }
     * }
     * }
     * }
     *
     * @apiErrorExample {json} Error-Response:
     * {
     * "_metadata": {
     * "status": "failed"
     * },
     * "errors": {
     * "organization_id": [
     * "This organization id is not associated with this member"
     * ]
     * }
     * }
     */
    public function saveApiDetails(Request $request)
    {
        /* @var $validator \Illuminate\Contracts\Validation\Validator */
        $validator = SaveOrganizationDetails::requestValidate($request->all());
        if ($validator->fails()) {
            $errors = $validator->errors();
            $result = ApiHelper::apiResponse(null, $errors);
            return $result;
        }

        if (!$validator->fails()) {
            $organization = Organization::find($request->organization_id);
            $owner = $organization->owner;
            $user = ApiHelper::getApiUser();
            $validator->after(function ($validator) use ($owner, $user) {
                if (isset($user->id) && $owner->id == $user->id) {
                } else {
                    $validator->getMessageBag()->add('organization_id', 'This organization id is not assosiated with this member');
                }
            });

            if (!$validator->fails()) {
                /**
                 * @var $organization Organization
                 */
                $organization = $this->orgService->saveDetails($request);
                $organizationDetails = $organization->details;
                $physicalAddress = $organizationDetails->physicalAddress;
                $postalAddress = $organization->postalAddress;
                $response = ApiHelper::apiResponse($organization);
                return $response;
            } else {
                $result = ApiHelper::apiResponse([], $validator->errors(), 'Invalid Permissions');
                return response($result, 400);
            }
        }
        $result = ApiHelper::apiResponse([], $validator->errors(), 'Validation Failed');
        return response($result, 400);
    }

    /**
     * @param api_token
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @api {Post} /org/get-org-list  [[val-03-03]] Get Organization List of User
     * @apiVersion 0.1.0
     * @apiName [[val-03-03]] Get Organization List of User
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Accept": "application/json",
     *       "Authorization: "Bearer {access_token}"
     *     }
     * @apiGroup Organization
     * @apiPermission Secured (Super Admin, Administrator)
     * @apiDescription Send access token and get List of Organizations against that user.
     *
     * @apiSuccessExample {json} Success-Example:
     *{
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
     * "logo": "http://api.memberme.me/storage/organization/logo/517ff6952f596587acde82618ed79142.png"
     * },
     * "status": 1,
     * "created_at": "2017-11-11 18:28:52",
     * "updated_at": "2017-11-15 11:17:21",
     * "details": {
     * "id": 1,
     * "organization_id": 15552,
     * "bio": null,
     * "contact_name": "Faisal ARif",
     * "contact_email": "test@memberme.me",
     * "contact_phone": "22222",
     * "office_phone": "223322233",
     * "industry": 1,
     * "account_no": "15552",
     * "logo": null,
     * "cover": null,
     * "physical_address_id": "1",
     * "postal_address_id": "1",
     * "gst_number": null,
     * "starting_member": "1",
     * "starting_receipt": "1",
     * "next_member": "1",
     * "data": null,
     * "created_at": "2017-11-11 18:48:50",
     * "updated_at": "2017-11-15 11:25:30"
     * }
     * },
     * {
     * "id": 15553,
     * "name": "Test Organization 2",
     * "current": null,
     * "user_id": 2,
     * "data": null,
     * "status": 1,
     * "created_at": "2017-11-11 18:28:52",
     * "updated_at": "2017-11-12 11:14:02",
     * "details": {
     * "id": 3,
     * "organization_id": 15553,
     * "bio": null,
     * "contact_name": "Faisal ARif",
     * "contact_email": "test@memberme.me",
     * "contact_phone": "03456888969",
     * "office_phone": "223322233",
     * "industry": 1,
     * "account_no": "15553",
     * "logo": null,
     * "cover": null,
     * "physical_address_id": "6",
     * "postal_address_id": "5",
     * "gst_number": null,
     * "starting_member": "1",
     * "starting_receipt": "1",
     * "next_member": "1",
     * "data": null,
     * "created_at": "2017-11-12 11:14:02",
     * "updated_at": "2017-11-12 11:14:02"
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
     * "id": 15555,
     * "name": "Org",
     * "current": 1,
     * "user_id": 3,
     * "data": {
     * "logo": "http://api.memberme.me/storage/organization/logo/0f9020b218c796df865cb19400e2bec3.png"
     * },
     * "status": 1,
     * "created_at": "2017-11-11 19:06:47",
     * "updated_at": "2017-11-13 08:02:27",
     * "details": {
     * "id": 2,
     * "organization_id": 15555,
     * "bio": null,
     * "contact_name": "names",
     * "contact_email": "maksuperlink@gmail.com",
     * "contact_phone": "123",
     * "office_phone": "123",
     * "industry": 1,
     * "account_no": "15555",
     * "logo": null,
     * "cover": null,
     * "physical_address_id": "3",
     * "postal_address_id": "2",
     * "gst_number": "1321213",
     * "starting_member": null,
     * "starting_receipt": null,
     * "next_member": null,
     * "data": null,
     * "created_at": "2017-11-11 19:07:55",
     * "updated_at": "2017-11-15 08:26:38"
     * }
     * },
     * {
     * "id": 15556,
     * "name": "Org1",
     * "current": 1,
     * "user_id": 7,
     * "data": null,
     * "status": 1,
     * "created_at": "2017-11-13 20:17:15",
     * "updated_at": "2017-11-13 20:18:38",
     * "details": {
     * "id": 4,
     * "organization_id": 15556,
     * "bio": null,
     * "contact_name": "names",
     * "contact_email": "maksuperlink+1@gmail.com",
     * "contact_phone": "1234",
     * "office_phone": "3423456",
     * "industry": 2,
     * "account_no": "15556",
     * "logo": null,
     * "cover": null,
     * "physical_address_id": "8",
     * "postal_address_id": "7",
     * "gst_number": null,
     * "starting_member": null,
     * "starting_receipt": null,
     * "next_member": null,
     * "data": null,
     * "created_at": "2017-11-13 20:18:38",
     * "updated_at": "2017-11-14 10:33:14"
     * }
     * },
     * {
     * "id": 15557,
     * "name": "Org 2",
     * "current": 1,
     * "user_id": 8,
     * "data": null,
     * "status": 1,
     * "created_at": "2017-11-13 20:23:03",
     * "updated_at": "2017-11-13 20:24:40",
     * "details": {
     * "id": 5,
     * "organization_id": 15557,
     * "bio": null,
     * "contact_name": "name",
     * "contact_email": "maksuperlink+2@gmail.com",
     * "contact_phone": "323423",
     * "office_phone": "34234",
     * "industry": 1,
     * "account_no": "15557",
     * "logo": null,
     * "cover": null,
     * "physical_address_id": "10",
     * "postal_address_id": "9",
     * "gst_number": null,
     * "starting_member": null,
     * "starting_receipt": null,
     * "next_member": null,
     * "data": null,
     * "created_at": "2017-11-13 20:24:40",
     * "updated_at": "2017-11-13 20:24:40"
     * }
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
     * "id": 15559,
     * "name": "Org 4",
     * "current": 1,
     * "user_id": 10,
     * "data": null,
     * "status": 2,
     * "created_at": "2017-11-14 17:24:26",
     * "updated_at": "2017-11-14 17:24:26",
     * "details": null
     * },
     * {
     * "id": 15560,
     * "name": "Org 5",
     * "current": 1,
     * "user_id": 11,
     * "data": null,
     * "status": 2,
     * "created_at": "2017-11-14 17:25:15",
     * "updated_at": "2017-11-14 17:25:15",
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
     * "error" : "unauthenticated"
     * }
     */
    public function getList(Request $request)
    {
        /* @var $user User */
        $user = ApiHelper::getApiUser();

        if ($user->hasRole(IUserType::SUPER_ADMIN)) {
            $orgList = $this->orgService->all();
        } else {
            $orgList = $this->orgService->getAllOrg($user->id);
        }

        if ($orgList->count())
            $result = ApiHelper::apiResponse($orgList);
        else
            $result = ApiHelper::apiResponse(null, ['user_id' => 'No organizations found against this user']);

        return response($result, 200);

    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @api {post} /org/set-current-org [[val-03-04]] Set Current Organization
     * @apiVersion 0.1.0
     * @apiName [[val-03-04]] Set Current Organization
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Accept": "application/json",
     *       "Authorization: "Bearer {access_token}"
     *     }
     * @apiGroup Organization
     * @apiParam {number} org_id Organization Id
     * @apiPermission Secured (Any registered user)
     * @apiDescription Set an organization by id as current from organization list of that user
     *
     * @apiSuccessExample {json} Success-Example:
     * {
     * "_metadata": {
     * "status": "passed"
     * },
     * "pending_org": null,
     * "message": "Test Organization 2 is now in use",
     * "data": {
     * "id": 15553,
     * "name": "Test Organization 2",
     * "current": null,
     * "user_id": 2,
     * "data": null,
     * "status": 1,
     * "created_at": "2017-11-11 18:28:52",
     * "updated_at": "2017-11-12 11:14:02"
     * }
     * }
     *
     * @apiErrorExample {json} Error-Response:
     * {
     * "_metadata": {
     * "status": "failed"
     * },
     * "errors": {
     * "org_id": [
     * "This Organization is not Enabled for this user",
     * "This Organization is not Assosiated with this member"
     * ]
     * }
     * }
     */
    public function setCurrentOrganization(Request $request)
    {
        $user = ApiHelper::getApiUser();

        $validationRules = [
            'org_id' => 'exists:organizations,id',
        ];

        $organization = null;
        $validator = Validator($request->all(), $validationRules);
        if (!$validator->fails()) {
            $validator->after(function () use ($validator, $request, $user) {
                if ($user->hasRole(IUserType::SUPER_ADMIN)) {
                    //todo check if organization is not disable for this person
                } else {
//                    $organization = $this->orgService->findByUserId($request->org_id , $user->id);   //checking organization from Organizations table, (for admin's only)
                    $organization = $this->orgService->getByIdWithRole($user->id, $request->org_id);
                    if (empty($organization)) {
                        $validator->getMessageBag()->add('org_id', ' This user is not associated with this Organization: ' . $request->org_id);
                    } else {
                        if ($organization->id !== 15550 && $organization->plan_payment_status != IStatus::ACTIVE) {
                            $validator->getMessageBag()->add('org_id', 'Organization ' . $organization->name . ' Payment is pending.');
                        }
                    }
                    $checkOrg = $this->orgService->checkOrgAgainstUser($request->org_id, $user->id);
                    if (empty($checkOrg)) {
                        $validator->getMessageBag()->add('org_id', 'This Organization is not Enabled for this user');
                        $result = ApiHelper::apiResponse(null, $validator->errors());
                        return response($result, 500);
                    }
                }
            });
            if (!$validator->fails()) {
                if ($user->hasRole(IUserType::SUPER_ADMIN)) {
                    $organization = $this->orgService->setAdminCurrentOrg($user->id, $request->org_id);
                } else {

//                    $organization = $this->orgService->findByUserId ($request->org_id , $user->id);
//                    $organization = $this->orgService->setCurrentOrg($user->id, $organization);
                    $isCurrent = $this->orgService->setCurrentOrg($user->id, $request->org_id);
                    if ($isCurrent) {
                        $organization = Organization::find($request->org_id);

                        //saving last login for organization.
                        Organization::where('id', $organization->id)->update(['last_login' => Carbon::now()]);

                        //saving last login for user.
                        $userRepo =  new UserRepository();
                        $userRepo->addLastLogin($user,$organization);
                    }
                }
                $result = ApiHelper::apiResponse($organization, null, $organization->name . ' is now in use');
                return response($result, 200);

            } else {
                $errors = $validator->errors();
                $result = ApiHelper::apiResponse(null, $errors, 'Authentication Error');
                return response($result, IResponseCode::INVALID_PARAMS);
            }

        } else {
            $errors = $validator->errors();
            $result = ApiHelper::apiResponse(null, $errors, 'Authentication Error');
            return response($result, 400);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @api {post} /org/get-current-org [[val-03-05]] Get Current Organization
     * @apiVersion 0.1.0
     * @apiName [[val-03-05]] Get Current Organization
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Accept": "application/json",
     *       "Authorization: "Bearer {access_token}"
     *     }
     * @apiGroup Organization
     * @apiPermission Secured (Any registered user)
     * @apiDescription Get an organization by id as current from organization list of that user
     *
     * @apiSuccessExample {json} Success-Example:
     * {
     * "_metadata": {
     * "status": "passed"
     * },
     * "pending_org": null,
     * "message": "Your Current Organization",
     * "data": {
     * "id": 15553,
     * "name": "Test Organization 2",
     * "current": null,
     * "user_id": 2,
     * "data": null,
     * "status": 1,
     * "created_at": "2017-11-11 18:28:52",
     * "updated_at": "2017-11-12 11:14:02"
     * }
     * }
     *
     * @apiErrorExample {json} Error-Response:
     * {
     * "message": "Unauthenticated."
     * }
     */
    public function getCurrentOrganization(Request $request)
    {
        $user = ApiHelper::getApiUser();
        if ($user->hasRole(IUserType::SUPER_ADMIN)) {
            $org = $this->orgService->getAdminCurrentOrg($user->id);
        } else {
//            $org = $this->orgService->getCurrentOrg($user->id);
            $org = $this->orgService->findCurrentOrganization($user->id);
        }
        if ($org) {

            if (isset($org->data) && !empty($org->data)) {
                $org->data = json_decode(unserialize($org->data));
            } else {
                $org->data = null;
            }
            $result = ApiHelper::apiResponse($org, null, 'Your Current Organization');
            return response($result, 200);
        } else {
            $result = ApiHelper::apiResponse(null, ['user' => 'No current Organization for this user']);
            return response($result, 401);
        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @api {post} /admin/org/upload-avatar [[val-03-06]] Upload Avatar
     * @apiVersion 0.1.0
     * @apiName [[val-03-06]] Upload Avatar
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Accept": "application/json",
     *       "Authorization: "Bearer {access_token}"
     *     }
     * @apiGroup Organization
     * @apiParam {number} organization_id organization
     * @apiParam {file} logo Selected logo for organization
     * @apiPermission Secured (Super Admin, Administrator, Manager)
     * @apiDescription upload a logo for a organization.
     *
     * @apiSuccessExample {json} Success-Example:
     * {
     * "_metadata": {
     * "status": "passed"
     * },
     * "pending_org": null,
     * "data": {
     * "id": 15552,
     * "name": "Random Founders",
     * "current": 1,
     * "user_id": 2,
     * "data": {
     * "logo": "http://api.memberme.me/storage/organization/logo/517ff6952f596587acde82618ed79142.png"
     * },
     * "status": 1,
     * "created_at": "2017-11-11 18:28:52",
     * "updated_at": "2017-11-15 11:17:21"
     * },
     * "message": "Organization Logo Updated Successfully"
     * }
     *
     * @apiErrorExample {json} Error-Response:
     * {
     * "_metadata": {
     * "status": "failed"
     * },
     * "errors": {
     * "permissions": "This user is not manager of this organization"
     * }
     * }
     */
    public function uploadLogo(Request $request)
    {

        $allowedImageExtensionArray = ['jpeg', 'bmp', 'png', 'JPG'];

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'logo' => 'mimes:' . implode(",", $allowedImageExtensionArray) . '|max:20000',
        ];

        $validator = Validator($request->all(), $validationRules);
        if (!$validator->fails()) {

            $validator->after(function ($query) use ($validator) {

            });
            $file = $request->file('logo');
            $name = $file->getClientOriginalName();
            $name = md5($name) . '.' . $file->getClientOriginalExtension();
            $path = '/logo/' . $name;
            Storage::put($path, File::get($file->getRealPath()));
//            $url = config('filesystems.disks.local.url').$path;
            $url = Storage::disk('local')->url($path);

            $organization = Organization::find($request->organization_id);
            $organization->data = ['logo' => $url];
            $organization->update();
            $result = ApiHelper::apiResponse($organization, null, 'Organization Logo Updated Successfully');
            return response($result, 200);
        } else {
            $result = ApiHelper::apiResponse(null, $validator->errors());
            return response($result, 500);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * @api {post} /admin/org/get-org [[val-03-07]] Get Organization Details
     *
     * @apiVersion 0.1.0
     * @apiName [[val-03-07]] Get Organization Details
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Accept": "application/json",
     *       "Authorization: "Bearer {access_token}"
     *     }
     * @apiGroup Organization
     * @apiPermission Secured (Super Admin, Administrator, Manager)
     * @apiDescription Get Organization Details by Id
     *
     * @apiSuccessExample {json} Success-Example:
     *{
     * "_metadata": {
     * "status": "passed"
     * },
     * "pending_org": null,
     * "data": {
     * "id": 15552,
     * "name": "Random Founders",
     * "current": 1,
     * "user_id": 2,
     * "data": {
     * "logo": "http://api.memberme.me/storage/organization/logo/517ff6952f596587acde82618ed79142.png"
     * },
     * "status": 1,
     * "created_at": "2017-11-11 18:28:52",
     * "updated_at": "2017-11-15 11:17:21",
     * "details": {
     * "id": 1,
     * "organization_id": 15552,
     * "bio": null,
     * "contact_name": "Faisal ARif",
     * "contact_email": "test@memberme.me",
     * "contact_phone": "03456888969",
     * "office_phone": "223322233",
     * "industry": 1,
     * "account_no": "15552",
     * "logo": null,
     * "cover": null,
     * "physical_address_id": "1",
     * "postal_address_id": "1",
     * "gst_number": null,
     * "starting_member": "1",
     * "starting_receipt": "1",
     * "next_member": "1",
     * "data": null,
     * "created_at": "2017-11-11 18:48:50",
     * "updated_at": "2017-11-15 10:50:23",
     * "physical_address": {
     * "id": 1,
     * "address1": "some postal address",
     * "address2": "some second postal address",
     * "suburb": "subrub",
     * "postal_code": "234",
     * "city": "city",
     * "region": "region",
     * "country_id": 89,
     * "latitude": "342323.2",
     * "longitude": "234223.3",
     * "status_id": "1",
     * "item_id": 15552,
     * "address_type_id": 62,
     * "item_type_id": 81,
     * "created_at": "2017-11-11 18:48:50",
     * "updated_at": "2017-11-15 10:50:23"
     * },
     * "postal_address": {
     * "id": 1,
     * "address1": "some postal address",
     * "address2": "some second postal address",
     * "suburb": "subrub",
     * "postal_code": "234",
     * "city": "city",
     * "region": "region",
     * "country_id": 89,
     * "latitude": "342323.2",
     * "longitude": "234223.3",
     * "status_id": "1",
     * "item_id": 15552,
     * "address_type_id": 62,
     * "item_type_id": 81,
     * "created_at": "2017-11-11 18:48:50",
     * "updated_at": "2017-11-15 10:50:23"
     * }
     * },
     * "owner": {
     * "id": 2,
     * "first_name": "Faisal",
     * "last_name": "Arif",
     * "middle_name": null,
     * "email": "test@memberme.me",
     * "contact_no": "0343543524",
     * "address_id": null,
     * "bio": null,
     * "user_type_id": 3,
     * "status_id": 1,
     * "api_token": "mYvhOk4YERkiSOvXB7rppnvrFzx8yfeoc0KE1eY0jZRH0UImU4iONIkTzqMf",
     * "verify": 1,
     * "notes": null,
     * "activate": 1,
     * "data": null,
     * "created_at": "2017-11-11 18:35:33",
     * "updated_at": "2017-11-12 20:09:06"
     * }
     * },
     * "message": "Organization Details"
     * }
     *
     * @apiErrorExample {json} Error-Response:
     *{
     * "_metadata": {
     * "status": "failed"
     * },
     * "errors": {
     * "organization_id": [
     * "The selected organization id is invalid."
     * ]
     * }
     * }
     *
     *
     */
    public function getById(Request $request)
    {
        $validationRules = [
            'organization_id' => 'required|exists:organizations,id'
        ];
        $validator = Validator($request->all(), $validationRules);

        if (!$validator->fails()) {
            $organization = $this->orgService->getById($request->organization_id);
            $validator->after(function () use ($validator, $request, $organization) {
                if (empty($organization)) {
                    $validator->getMessageBag()->add('organization_id', 'Invalid Param');
                }
            });

            if (!$validator->fails()) {
                $result = ApiHelper::apiResponse($organization, null, 'Organization Details');
                return response($result, 200);
            }
        }
        $result = ApiHelper::apiResponse(null, $validator->errors());
        return response($result, 500);

    }

    /**
     * @param Request $request
     * @return mixed
     * @api {post} /admin/org/save-open-field [[val-03-08]] Update Org Details
     * @apiVersion 0.1.0
     * @apiName [[val-03-08]] Update Org Details
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Accept": "application/json",
     *       "Authorization: "Bearer {access_token}"
     *     }
     * @apiGroup Organization
     * @apiParam {number} organization_id Organization Id
     * @apiParam {string} contact_name Contact name ('contact_name','contact_email','contact_phone','office_phone','industry','gst_number')
     * @apiPermission Secured (Super Admin, Administrator, Manager )
     * @apiDescription Save one field at on time against an organization.
     *
     * @apiSuccessExample {json} Success-Example:
     *
     * {
     * "_metadata": {
     * "status": "passed"
     * },
     * "pending_org": null,
     * "data": {
     * "id": 15552,
     * "name": "Random Founders",
     * "current": 1,
     * "user_id": 2,
     * "data": {
     * "logo": "http://api.memberme.me/storage/organization/logo/517ff6952f596587acde82618ed79142.png"
     * },
     * "status": 1,
     * "created_at": "2017-11-11 18:28:52",
     * "updated_at": "2017-11-15 11:17:21",
     * "details": {
     * "id": 1,
     * "organization_id": 15552,
     * "bio": null,
     * "contact_name": "Faisal ARif",
     * "contact_email": "test@memberme.me",
     * "contact_phone": "22222",
     * "office_phone": "223322233",
     * "industry": 1,
     * "account_no": "15552",
     * "logo": null,
     * "cover": null,
     * "physical_address_id": "1",
     * "postal_address_id": "1",
     * "gst_number": null,
     * "starting_member": "1",
     * "starting_receipt": "1",
     * "next_member": "1",
     * "data": null,
     * "created_at": "2017-11-11 18:48:50",
     * "updated_at": "2017-11-15 11:25:30",
     * "physical_address": {
     * "id": 1,
     * "address1": "some postal address",
     * "address2": "some second postal address",
     * "suburb": "subrub",
     * "postal_code": "234",
     * "city": "city",
     * "region": "region",
     * "country_id": 89,
     * "latitude": "342323.2",
     * "longitude": "234223.3",
     * "status_id": "1",
     * "item_id": 15552,
     * "address_type_id": 62,
     * "item_type_id": 81,
     * "created_at": "2017-11-11 18:48:50",
     * "updated_at": "2017-11-15 10:50:23"
     * },
     * "postal_address": {
     * "id": 1,
     * "address1": "some postal address",
     * "address2": "some second postal address",
     * "suburb": "subrub",
     * "postal_code": "234",
     * "city": "city",
     * "region": "region",
     * "country_id": 89,
     * "latitude": "342323.2",
     * "longitude": "234223.3",
     * "status_id": "1",
     * "item_id": 15552,
     * "address_type_id": 62,
     * "item_type_id": 81,
     * "created_at": "2017-11-11 18:48:50",
     * "updated_at": "2017-11-15 10:50:23"
     * }
     * },
     * "owner": {
     * "id": 2,
     * "first_name": "Faisal",
     * "last_name": "Arif",
     * "middle_name": null,
     * "email": "test@memberme.me",
     * "contact_no": "0343543524",
     * "address_id": null,
     * "bio": null,
     * "user_type_id": 3,
     * "status_id": 1,
     * "api_token": "mYvhOk4YERkiSOvXB7rppnvrFzx8yfeoc0KE1eY0jZRH0UImU4iONIkTzqMf",
     * "verify": 1,
     * "notes": null,
     * "activate": 1,
     * "data": null,
     * "created_at": "2017-11-11 18:35:33",
     * "updated_at": "2017-11-12 20:09:06"
     * }
     * },
     * "message": "contact_phone has been updated Successfully"
     * }
     *
     * @apiErrorExample {json} Error-Response:
     *{
     * "_metadata": {
     * "status": "failed"
     * },
     * "errors": {
     * "organization_id": [
     * "The selected organization id is invalid."
     * ]
     * }
     * }
     *
     */
    public function saveField(Request $request)
    {

        $validationRules = [
            'name' => 'min:3',
            'contact_email' => 'email',
            'organization_id' => 'required|exists:organizations,id',
            'contact_name' => 'min:3',
            'contact_phone' => 'numeric|min:3',
            'office_phone' => 'numeric|min:3',
            'industry' => 'numeric|exists:records,id',
            'gst_number' => 'numeric|min:1',
            'next_member' => 'numeric',
        ];

        $fields = $request->input();
        if (count($fields) > 2) {
            return response(ApiHelper::apiResponse(null, ['error' => 'You can not edit more then one field']));
        }

        $validator = Validator($request->all(), $validationRules);

        $orgDetailsFeild = null;
        $orgDetailsValue = null;
        $validKey = false;
        if (!$validator->fails()) {
            foreach ($fields as $key => $value) {
                if ($key != 'organization_id') {
                    /*organization Details*/
                    if (in_array($key, OrganizationRepository::AuthorizedFields())) {
                        $orgDetailsFeild = $key;
                        $orgDetailsValue = $value;
                        $validKey = true;
                        if ($orgDetailsFeild == 'next_member') {
                            $organization = Organization::find($request->organization_id);
                            $organizationDetail = $organization->details;
                            if (empty($organizationDetail)) {
                                $organizationDetail = new OrganizationDetail();
                                $organizationDetail->organization_id = array_get($organization, 'id');
                                $organizationDetail->save();
                            }
                            $memberHavingNextNumber = $organization->members()->where('member_id', $request->next_member)->first();
                            $memberRepo = new MemberRepository();
                            $missingNumber = $memberRepo->findFirstMissingNumber($request->organization_id);

                            if (!empty($memberHavingNextNumber)) {
                                /*if ($lastMember) {
                                    $validator->getMessageBag()->add('next_member', 'Next Member Unavailable, your next member should be greater then ' . $lastMember->member_id);
                                    return response(ApiHelper::apiResponse(null, $validator->errors()), IResponseCode::INVALID_PARAMS);
                                }*/
                                $validator->getMessageBag()->add('next_member', 'Member Number Unavailable. &nbsp; The member has already been used the next available member is ' . $missingNumber);
                                return api_response(null, $validator->errors(), IResponseCode::INVALID_PARAMS);
                            }
                        }
                    } else {
                        $validator->getMessageBag()->add($key, 'Invalid Field Name');
                        return response(ApiHelper::apiResponse(null, $validator->errors()), 400);
                    }
                }
            }
            if ($validKey) {
                $organizationDetails = OrganizationDetail::whereOrganizationId($request->organization_id)->firstOrCreate(['organization_id' => $request->organization_id]);
                if ($organizationDetails) {
                    $organizationDetails = $this->orgService->saveField($organizationDetails, $orgDetailsFeild, $orgDetailsValue);
                }
                $organization = $this->orgService->getById($request->organization_id);
                return response(ApiHelper::apiResponse($organization, null, $orgDetailsFeild . ' has been updated Successfully'));
            }
        } else {
            return response(ApiHelper::apiResponse(null, $validator->errors()), 500);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * @api {post} admin/org/update-postal-address [[val-03-09]] Update Postal Address
     * @apiVersion 0.1.0
     * @apiName [[val-03-09]] Update Postal Address
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Accept": "application/json",
     *       "Authorization: "Bearer {access_token}"
     *     }
     * @apiGroup Organization
     * @apiParam {number} organization_id Mandatory Organization Id
     * @apiParam {number} postal_country Mandatory postal Country_id of Organization
     * @apiParam {string} postal_first_address Optional first address line of Organization
     * @apiParam {string} postal_suburb Optional postal_suburb of Organization
     * @apiParam {string} postal_suburb Optional postal_suburb of Organization
     * @apiParam {string} postal_city Mandatory postal City of Organization
     * @apiParam {string} postal_region Mandatory postal_region of Organization
     * @apiParam {string} postal_postal_code Mandatory postal_postal_code of Organization
     * @apiPermission Secured (Super Admin, Administrator, manager)
     * @apiDescription Update postal address of an organization
     *
     * @apiSuccessExample {json} Success-Example:
     * {
     * "_metadata": {
     * "status": "passed"
     * },
     * "pending_org": null,
     * "data": {
     * "id": 15552,
     * "name": "Random Founders",
     * "current": 1,
     * "user_id": 2,
     * "data": {
     * "logo": "http://api.memberme.me/storage/organization/logo/517ff6952f596587acde82618ed79142.png"
     * },
     * "status": 1,
     * "created_at": "2017-11-11 18:28:52",
     * "updated_at": "2017-11-15 11:17:21",
     * "details": {
     * "id": 1,
     * "organization_id": 15552,
     * "bio": null,
     * "contact_name": "Faisal ARif",
     * "contact_email": "test@memberme.me",
     * "contact_phone": "22222",
     * "office_phone": "223322233",
     * "industry": 1,
     * "account_no": "15552",
     * "logo": null,
     * "cover": null,
     * "physical_address_id": "1",
     * "postal_address_id": "1",
     * "gst_number": null,
     * "starting_member": "1",
     * "starting_receipt": "1",
     * "next_member": "1",
     * "data": null,
     * "created_at": "2017-11-11 18:48:50",
     * "updated_at": "2017-11-15 11:25:30",
     * "physical_address": {
     * "id": 1,
     * "address1": "some address",
     * "address2": "some second address",
     * "suburb": "212",
     * "postal_code": "52000",
     * "city": "wazirabad",
     * "region": "punjab",
     * "country_id": 1,
     * "latitude": "342323.2",
     * "longitude": "234223.3",
     * "status_id": "1",
     * "item_id": 15552,
     * "address_type_id": 62,
     * "item_type_id": 81,
     * "created_at": "2017-11-11 18:48:50",
     * "updated_at": "2017-11-15 11:30:56"
     * },
     * "postal_address": {
     * "id": 1,
     * "address1": "some address",
     * "address2": "some second address",
     * "suburb": "212",
     * "postal_code": "52000",
     * "city": "wazirabad",
     * "region": "punjab",
     * "country_id": 1,
     * "latitude": "342323.2",
     * "longitude": "234223.3",
     * "status_id": "1",
     * "item_id": 15552,
     * "address_type_id": 62,
     * "item_type_id": 81,
     * "created_at": "2017-11-11 18:48:50",
     * "updated_at": "2017-11-15 11:30:56"
     * }
     * },
     * "owner": {
     * "id": 2,
     * "first_name": "Faisal",
     * "last_name": "Arif",
     * "middle_name": null,
     * "email": "test@memberme.me",
     * "contact_no": "0343543524",
     * "address_id": null,
     * "bio": null,
     * "user_type_id": 3,
     * "status_id": 1,
     * "api_token": "mYvhOk4YERkiSOvXB7rppnvrFzx8yfeoc0KE1eY0jZRH0UImU4iONIkTzqMf",
     * "verify": 1,
     * "notes": null,
     * "activate": 1,
     * "data": null,
     * "created_at": "2017-11-11 18:35:33",
     * "updated_at": "2017-11-12 20:09:06"
     * }
     * },
     * "message": "Address Updated Successfully"
     * }
     *
     * @apiErrorExample {json} Error-Response:
     * {
     * "_metadata": {
     * "status": "failed"
     * },
     * "errors": {
     * "postal_country": [
     * "The postal country must be a number."
     * ]
     * }
     * }
     *
     */
    public function updatePhysicalAddress(Request $request)
    {
        $validationRules = [
            'organization_id' => 'required|exists:organization_details,organization_id',
            'physical_country' => 'required|numeric',
            'physical_first_address' => 'required',
            'physical_suburb' => 'required',
            'physical_city' => 'required',
            //  'physical_region' => 'required',
            'physical_postal_code' => 'required',
        ];

        $validator = Validator($request->all(), $validationRules);
        $physicalAddress = null;
        if (!$validator->fails()) {
            $validator->after(function ($validator) use ($request, &$physicalAddress) {
                $organizationDetail = OrganizationDetail::where('organization_id', $request->organization_id)->first();
                $physicalAddress = $organizationDetail->physicalAddress;
                if (empty($physicalAddress)) {
//                    $validator->getMessageBag()->add('organization_id','No Physical Address for this Organization');
                    $physicalAddress = new Address();

                    $physicalAddress->status_id = IStatus::ACTIVE;
                    $physicalAddress->item_id = $request->organization_id;
                    $physicalAddress->address_type_id = AddressType::PHYSICAL_ADDRESS;
                    $physicalAddress->item_type_id = AddressType::ORGANIZATION;
                    $physicalAddress->save();
                }
            });
            if (!$validator->fails()) {
                $physicalAddress->country_id = $request->physical_country;
                $physicalAddress->address1 = $request->physical_first_address;
                $physicalAddress->address2 = $request->physical_second_address;
                $physicalAddress->suburb = $request->physical_suburb;
                $physicalAddress->city = $request->physical_city;
                $physicalAddress->region = $request->physical_region;
                $physicalAddress->latitude = $request->physical_latitude;
                $physicalAddress->longitude = $request->physical_longitude;
                $physicalAddress->status_id = IStatus::ACTIVE;
                $physicalAddress->postal_code = $request->physical_postal_code;
                $physicalAddress->item_id = $request->organization_id;
                $physicalAddress->address_type_id = AddressType::PHYSICAL_ADDRESS;
                $physicalAddress->item_type_id = AddressType::ORGANIZATION;
                $physicalAddress->save();
                $result = $this->orgService->getById($request->organization_id);

                return response(ApiHelper::apiResponse($result, null, 'Address Updated Successfully'), 200);
            }
        }
        return response(ApiHelper::apiResponse(null, $validator->errors()), 500);
    }

    /**
     * @param Request $request
     * @return mixed
     * @api {post} /admin/org/update-physical-address [[val-03-10]] Update Physical Address
     * @apiVersion 0.1.0
     * @apiName [[val-03-10]] Update Physical Address
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Accept": "application/json",
     *       "Authorization: "Bearer {access_token}"
     *     }
     * @apiGroup Organization
     * @apiParam {number} organization_id Mandatory Organization Id
     *
     * @apiParam {number} physical_country Mandatory Physical Country_id of Organization
     * @apiParam {string} physical_first_address Optional first address line of Organization
     * @apiParam {string} physical_suburb Optional physical_suburb of Organization
     * @apiParam {string} physical_suburb Optional physical_suburb of Organization
     * @apiParam {string} physical_city Mandatory Physical City of Organization
     * @apiParam {string} physical_region Mandatory physical_region of Organization
     * @apiParam {string} physical_postal_code Mandatory physical_postal_code of Organization
     * @apiParam {string} physical_latitude Mandatory physical_latitude of Organization
     * @apiParam {string} physical_longitude Mandatory physical_longitude of Organization
     * @apiPermission Secured (Super Admin, Administrator, manager)
     * @apiDescription Update physical address of an organization
     *
     * @apiSuccessExample {json} Success-Example:
     * {
     * "_metadata": {
     * "status": "passed"
     * },
     * "pending_org": null,
     * "data": {
     * "id": 15552,
     * "name": "Random Founders",
     * "current": 1,
     * "user_id": 2,
     * "data": {
     * "logo": "http://api.memberme.me/storage/organization/logo/517ff6952f596587acde82618ed79142.png"
     * },
     * "status": 1,
     * "created_at": "2017-11-11 18:28:52",
     * "updated_at": "2017-11-15 11:17:21",
     * "details": {
     * "id": 1,
     * "organization_id": 15552,
     * "bio": null,
     * "contact_name": "Faisal ARif",
     * "contact_email": "test@memberme.me",
     * "contact_phone": "22222",
     * "office_phone": "223322233",
     * "industry": 1,
     * "account_no": "15552",
     * "logo": null,
     * "cover": null,
     * "physical_address_id": "1",
     * "postal_address_id": "1",
     * "gst_number": null,
     * "starting_member": "1",
     * "starting_receipt": "1",
     * "next_member": "1",
     * "data": null,
     * "created_at": "2017-11-11 18:48:50",
     * "updated_at": "2017-11-15 11:25:30",
     * "physical_address": {
     * "id": 1,
     * "address1": "some address",
     * "address2": "some second address",
     * "suburb": "212",
     * "postal_code": "52000",
     * "city": "wazirabad",
     * "region": "punjab",
     * "country_id": 1,
     * "latitude": "342323.2",
     * "longitude": "234223.3",
     * "status_id": "1",
     * "item_id": 15552,
     * "address_type_id": 62,
     * "item_type_id": 81,
     * "created_at": "2017-11-11 18:48:50",
     * "updated_at": "2017-11-15 11:30:56"
     * },
     * "postal_address": {
     * "id": 1,
     * "address1": "some address",
     * "address2": "some second address",
     * "suburb": "212",
     * "postal_code": "52000",
     * "city": "wazirabad",
     * "region": "punjab",
     * "country_id": 1,
     * "latitude": "342323.2",
     * "longitude": "234223.3",
     * "status_id": "1",
     * "item_id": 15552,
     * "address_type_id": 62,
     * "item_type_id": 81,
     * "created_at": "2017-11-11 18:48:50",
     * "updated_at": "2017-11-15 11:30:56"
     * }
     * },
     * "owner": {
     * "id": 2,
     * "first_name": "Faisal",
     * "last_name": "Arif",
     * "middle_name": null,
     * "email": "test@memberme.me",
     * "contact_no": "0343543524",
     * "address_id": null,
     * "bio": null,
     * "user_type_id": 3,
     * "status_id": 1,
     * "api_token": "mYvhOk4YERkiSOvXB7rppnvrFzx8yfeoc0KE1eY0jZRH0UImU4iONIkTzqMf",
     * "verify": 1,
     * "notes": null,
     * "activate": 1,
     * "data": null,
     * "created_at": "2017-11-11 18:35:33",
     * "updated_at": "2017-11-12 20:09:06"
     * }
     * },
     * "message": "Address Updated Successfully"
     * }
     *
     * @apiErrorExample {json} Error-Response:
     * {
     * "_metadata": {
     * "status": "failed"
     * },
     * "errors": {
     * "postal_country": [
     * "The postal country must be a number."
     * ]
     * }
     * }
     *
     */
    public function updatePostalAddress(Request $request)
    {
        $validationRules = [
            'organization_id' => 'required|exists:organization_details,organization_id',
            'postal_country' => 'required|numeric',
            'postal_first_address' => 'required',
            'postal_suburb' => 'required',
            'postal_city' => 'required',
            //'postal_region' => 'required',
            'postal_postal_code' => 'required',
        ];

        $validator = Validator($request->all(), $validationRules);
        $postalAddress = null;
        if (!$validator->fails()) {
            $validator->after(function ($validator) use ($request, &$postalAddress) {
                $organizationDetail = OrganizationDetail::where('organization_id', $request->organization_id)->first();
                $postalAddress = $organizationDetail->postalAddress;
                if (empty($postalAddress)) {
//                    $validator->getMessageBag()->add('organization_id','No postal Address for this Organization');
                    $postalAddress = new Address();
                }
            });
            if (!$validator->fails()) {
                $postalAddress->country_id = $request->postal_country;
                $postalAddress->address1 = $request->postal_first_address;
                $postalAddress->address2 = $request->postal_second_address;
                $postalAddress->suburb = $request->postal_suburb;
                $postalAddress->city = $request->postal_city;
                $postalAddress->region = $request->postal_region;
//                $postalAddress->latitude = $request->postal_latitude;
//                $postalAddress->longitude = $request->postal_longitude;
                $postalAddress->status_id = IStatus::ACTIVE;
                $postalAddress->postal_code = $request->postal_postal_code;
                $postalAddress->item_id = $request->organization_id;
                $postalAddress->address_type_id = AddressType::POSTAL_ADDRESS;
                $postalAddress->item_type_id = AddressType::ORGANIZATION;
                $postalAddress->save();
                $result = $this->orgService->getById($request->organization_id);

                return response(ApiHelper::apiResponse($result, null, 'Address Updated Successfully'), 200);
            }
        }

        return response(ApiHelper::apiResponse(null, $validator->errors()), 500);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @api {{post}} /admin/org/update-name [[val-03-11]] update Organization name
     * @apiName [[val-03-11]] update Organization name
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Accept": "application/json",
     *       "Authorization: "Bearer {access_token}"
     *     }
     * @apiGroup Organization
     * @apiParam {number} organization_id Mandatory Organization Id
     * @apiParam {string} name Mandatory Organization name
     * @apiDescription This call is for changing the name of the organization
     *
     */
    public function changeName(Request $request)
    {

        $validationRules = [
            'name' => 'min:3|required',
            'organization_id' => 'required|exists:organizations,id',
        ];

        $validator = Validator($request->all(), $validationRules);
        if (!$validator->fails()) {
            $organization = Organization::find($request->organization_id);
            $organization->name = $request->name;
            $organization->update();
            $organization = $this->orgService->getById($request->organization_id);
            return response(ApiHelper::apiResponse($organization, null, 'Organization name changed successfully'), 200);
        } else {
            return response(ApiHelper::apiResponse(null, $validator->errors()), 500);
        }
    }


    public function updateOptionField(Request $request)
    {

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
        ];

        $fields = $request->input();

        //region If Fields is more then 1 return back
        if (count($fields) > 3) {
            return response(ApiHelper::apiResponse(null, ['error' => 'You can not edit more then one field']), IResponseCode::PRECONDITION_FAILED);
        }
        //endregion

        $validator = Validator($request->all(), $validationRules);

        //region Validating and updating the fields
        $organizationOtherFeild = null;
        $organizationOtherFieldValue = null;
        $validKey = false;

        if (!$validator->fails()) {

            //region Validating Field Name
            foreach ($fields as $key => $value) {
                if ($key != 'organization_id') {
                    if (in_array($key, OrganizationOption::AuthorizedFields())) {
                        $organizationOtherFeild = $key;
                        $organizationOtherFieldValue = $value;
                        $validKey = true;
                    } else {
                        $validator->getMessageBag()->add($key, 'Invalid Field Name');
                        return api_error($validator->errors());
                    }
                }
            }
            //endregion

            if ($validKey) {
                $organizationOption = OrganizationOption::where('organization_id', $request->organization_id)->firstOrCreate(['organization_id' => $request->organization_id]);
                if ($organizationOption) {
                    $updatedorganizationOption = $this->orgService->saveOptionField($organizationOption, $organizationOtherFeild, $organizationOtherFieldValue);
                    $updatedorganizationOption->refresh();
                    return api_response($updatedorganizationOption, null, 'Organization Options Updated Successfully');
                } else {
                    return api_error(['organization_id' => 'No setting found']);
                }
            } else {
                return api_error(['invalid_field' => 'invalid field']);
            }
        } else {
            return api_error($validator->errors());
        }
        //endregion
    }

    public function setTimeZone(Request $request)
    {
        /* @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'timezone_id' => 'required|exists:timezones,id',
        ];

        $validator = Validator($request->all(), $validationRules);

        if (!$validator->fails()) {

            $validator->after(function ($validator) {
                //todo After Validation here
            });

            if (!$validator->fails()) {
                $organization->timezone_id = $request->get('timezone_id');
                $organization->save();

                return api_response($organization, null, 'Timezone has been set against this organization');
            } else {
                return api_error($validator->errors());
            }

        } else {
            return api_error($validator->errors());
        }
    }

    /**
     * Add payment type to the subscription
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addPaymentType(Request $request)
    {
        /* @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'organization_id' => 'exists:organizations,id|required',
            'name' => 'required',
            'id' => 'exists:payment_types,id'
        ];

        $validator = Validator($request->all(), $validationRules);

        if (!$validator->fails()) {

            $validator->after(function ($validator) {
                //todo After Validation here
            });

            if (!$validator->fails()) {
                $allPaymentTypes = $this->orgService->addPaymentType($organization, $request->all());
                return api_response($allPaymentTypes);
            } else {
                return api_error($validator->errors());
            }

        } else {
            return api_error($validator->errors());
        }
    }

    public function getPaymentTypes(Request $request)
    {
        /* @var $organization Organization */
        $organization = $request->get(Organization::NAME);
        return api_response($organization->paymentTypes);
    }

    public function insertSmsDetails(Request $request)
    {
        /* @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'url' => 'active_url',
        ];

        $fields = $request->input();

        //region If Fields is more then 1 return back
        if (count($fields) > 2) {
            return response(ApiHelper::apiResponse(null, ['error' => 'You can not edit more then one field']), IResponseCode::PRECONDITION_FAILED);
        }
        //endregion

        $validator = Validator($request->all(), $validationRules);

        $smsFeild = null;
        $smsFieldValue = null;
        $validKey = false;

        if (!$validator->fails()) {
            $validator->after(function ($validator) {
                //todo After Validation here
            });

            if (!$validator->fails()) {
                //region Validating Field Name
                foreach ($fields as $key => $value) {
                    if ($key != 'organization_id' && $key != 'id') {
                        if (in_array($key, OrganizationSmsSetting::AUTHORISED_FIELDS)) {
                            $smsFeild = $key;
                            $smsFieldValue = $value;
                            $validKey = true;
                        } else {
                            $validator->getMessageBag()->add($key, 'Invalid Field Name');
                            return response(ApiHelper::apiResponse(null, $validator->errors()), IResponseCode::INVALID_PARAMS);
                        }
                    }
                }
                //endregion

                if ($validKey) {
                    $smsSetting = $organization->smsSetting;
                    if (empty($smsSetting)) {
                        $smsSetting = new OrganizationSmsSetting();
                        $smsSetting->organization_id = $organization->id;
                        $smsSetting->save();
                    }
                    $smsSetting = $this->orgService->updateSmsField($smsSetting, $smsFeild, $smsFieldValue);
                    return api_response($smsSetting, null, 'Sms Setting Updated');
                }
            } else {
                return api_error($validator->errors());
            }

        } else {
            return api_error($validator->errors());
        }
    }

    public function sendSmsToGroup(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'group_id' => 'required|exists:groups,id',
            'message' => 'required'
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        /* @var $group Group */
        $group = $organization->groups()->find($request->get('group_id'));
        /* @var $smsList SmsList */


        /*$smsList = $group->smsList;
        if(!empty($smsList)){
            $smsListId = $smsList->ref_id;
        }else{
            $smsList = $this->orgService->addSmsList($group->name,$group);
            $smsListId = $smsList->ref_id;
        }*/

        $membersToAddList = $group->members()
            ->whereNotNull('contact_no')
            ->whereHas('others', function ($query) {
                $query->where('receive_sms', IStatus::ACTIVE);
            })
            ->select('contact_no', 'first_name', 'last_name', 'members.id')->get();

        if ($membersToAddList->isEmpty()) {
            throw new ApiException(null, ['error' => 'This group doesn\'t have any member with phone.']);
        }

        $list = $group->smsList;
        $listReady = true;

        if (empty($list)) {
            $name = md5($group->name) . str_replace(' ', '_', $group->name) . '.csv';

            $storageFilename = 'memberlist/' . $name;
            $filename = storage_path('app/public/images/' . $storageFilename);

            try {
                $handle = fopen($filename, 'a');
                fputcsv($handle, array('Mobile', 'Firstname', 'Lastname'));

                foreach ($membersToAddList as $item) {
                    $preparedNumber = prepare_number($item['contact_no']);
                    fputcsv($handle, array($preparedNumber, $item['first_name'], $item['last_name']));
                }
                fclose($handle);

            } catch (\Exception $e) {
                \Log::info($e->getMessage());
                throw new ApiException(null, ['error' => 'Cannot make sms list. Please contact administrator']);
            }
            try {
                $list = $this->orgService->addBulkContacts($organization, $group, $group->name, Storage::url($storageFilename));
            } catch (\Exception $exception) {
                dd($exception->getLine());
            }

            $tries = 0;
            do {
                sleep(1);
                $tries++;
                $listReady = $this->orgService->checkSmsListReady($organization, $list->ref_id, count($membersToAddList));
            } while ($listReady != true || $tries < 5);

            foreach ($membersToAddList as $member) {
                /* @var $smsListMember SmsListMember */
                $smsListMember = new SmsListMember();
                $smsListMember->member_id = $member->id;
                $smsListMember->group_id = $group->id;
                $smsListMember->sms_list_id = $list->id;
                $smsListMember->save();
            }
        }

        if ($listReady) {
            $listSentSms = $this->orgService->sendSmsToList($organization, $list, $request->get('message'));
        }

        return api_response([], null);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ApiException
     */
    public function saveKioskTemplate(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'kiosk_background_id' => 'required|exists:kiosk_backgrounds,id',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        $kioskTemplate = $this->orgService->saveKioskTemplate($organization, $request->all());

        return api_response($kioskTemplate);
    }

    /**
     * Return organization Password against organization_id
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getKioskPassword(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id'
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        return api_response(['id' => $organization->id, 'kiosk_password' => $organization->password]);
    }

    /**
     * return specific kiosk template.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getKioskById(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'template_no' => 'required',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        $kioskTemplate = $organization->kioskTemplates()->where('template_no', \request('template_no'))
            ->with([
                'background',
            ])->first();
        return api_response($kioskTemplate);
    }

    /**
     * Return all kiosk templates
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function getAllKioskTemplate(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        $kioskTemplates = $organization->kioskTemplates;

        return api_response($kioskTemplates);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSmsBalance(Request $request)
    {

        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id'
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }
        $balance = $this->orgService->getSmsBalance($organization);
        return api_response($balance);
    }

    /**
     * [[ val-03-29 ]] Get Esitmated Cost
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSmsCost(Request $request)
    {

        /** @var $organization Organization */

        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'group_id' => 'required|exists:groups,id',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        /* @var $smsSetting OrganizationSmsSetting */
        $smsSetting = $organization->smsSetting;

        if (empty($smsSetting)) {
            return api_error(['error' => 'This organization have not setup of Sms']);
        }

        $costPerSms = $smsSetting->sms_rate;
        if (empty($costPerSms)) {
            return api_error(['error' => 'Sorry, we are unable to calculate your cost. Please contact Administrator']);
        }

        /* @var $group Group */
        $group = $organization->groups()->where('id', $request->get('group_id'))->first();

        $memberCount = $group->members()
            ->whereNotNull('contact_no')
            ->whereHas('others', function ($query) {
                $query->where('receive_sms', IStatus::ACTIVE);
            })->get();

        $memberCount = count($memberCount);

        if (empty($memberCount)) {
            return api_error(['error' => 'This group have no member to send message']);
        }

        $estimatedCost = $memberCount * $costPerSms;

        return api_response([
            'message' => 'This will send ' . $memberCount . ' SMS' . PHP_EOL . 'The cost will be $' . number_format($estimatedCost, 3),
            'sms_cost' => number_format($estimatedCost, 3),
            'remaining_balance' => $smsSetting->sms_balance
        ]);


    }

    public function updateSettings(Request $request)
    {

        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);
        $subscriptionDateValidArray = implode(',', OrganizationSetting::SUBSCRIPTION_DROPDOWN_OPTION);
        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'subscription_start_date' => 'in:' . $subscriptionDateValidArray,
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        $organizationSettings = $this->orgService->updateOrganizationSettings($organization, $request->all());

        return api_response($organizationSettings);
    }

    public function getAllNotifications(Request $request)
    {
        $organization = $request->get(Organization::NAME);
        return api_response($organization->memberNotifications()
            ->orderBy('id', 'desc')
            ->with(['member' => function ($q) {
                $q->select('id', 'first_name', 'last_name', 'member_id');
            },
                'clickedByUser' => function ($q) {
                    $q->select('id','first_name');
                },
                'updatedByUser' => function ($q) {
                    $q->select('id','first_name');
                },
            ])
            ->limit(25)
            ->get());
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markNotificationAsSeen(Request $request)
    {
        /** @var $organization Organization */

        $organization = $request->get(Organization::NAME);

        $organization->memberNotifications()->update(['seen_date_time' => Carbon::now()]);  //updating organization member notifications

        return api_response(null, null, 'Notifications are set as seen');
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markNotificationAsClicked(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'member_notification_id' => 'required|exists:member_notifications,id',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        $user = ApiHelper::getApiUser();
        $organization->memberNotifications()
            ->where(['id' => $request->get('member_notification_id')])
            ->update(['clicked_date_time' => Carbon::now(), 'clicked_by_user_id' => array_get($user,'id')]);  //updating organization member notifications

        $notification = $organization->memberNotifications()->where('member_notifications.id', $request->get('member_notification_id'))
            ->with(['member' => function ($q) {
                $q->select('id', 'first_name', 'last_name', 'member_id');
            },
                'clickedByUser' => function ($q) {
                    $q->select('id','first_name');
                },
                'updatedByUser' => function ($q) {
                    $q->select('id','first_name');
                },
            ])
            ->first();

        return api_response($notification, null, 'Notifications Clicked');
    }


    public function getKioskVoucherParameterSettings(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $kioskVoucherParameterSettings = $organization->kioskVoucherParameter;
        return api_response($kioskVoucherParameterSettings);
    }

    /**
     * To add or update the sendgrid api key.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSendgridKey(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'sendgrid_api_key' => 'required',
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        $sendgridSetting = $organization->sendgridSetting;
        if (empty($sendgridSetting) && !empty($request->get('sendgrid_api_key'))) {
            $sendgridSetting = new SendgridSetting();
            $sendgridSetting->organization_id = $organization->id;
        }
        $sendgridSetting = $this->orgService->addSendgridApiKey($sendgridSetting, $request->all());

        return api_response($sendgridSetting);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateEmailTemplates(Request $request)
    {
        /** @var Organization $organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'email_name' => 'required',
            'template_id' => 'required',
            'email_type' => 'required|in:' . implode(',', EmailTemplate::EMAIL_TYPE),
            'send_email_date' => 'in:' . implode(',', EmailTemplate::SEND_EMAIL_DATE) . '|required_if:email_type,' . EmailTemplate::EMAIL_TYPE['SCHEDULED'],
            'send_email_time' => 'required_if:email_type,' . EmailTemplate::EMAIL_TYPE['SCHEDULED'],
            'before_or_after' => 'required_if:email_type,' . EmailTemplate::EMAIL_TYPE['SCHEDULED'],
//            'email_group' => 'required',
            'days' => 'required_if:before_or_after,' . EmailTemplate::BEFORE_OR_AFTER['BEFORE'] . '|required_if:before_or_after,' . EmailTemplate::BEFORE_OR_AFTER['AFTER'],
            'event' => 'required_if:email_type,' . EmailTemplate::EMAIL_TYPE['TRANSACTIONAL'],
            'group_ids' => 'array',
            'group_ids.*' => 'exists:groups,id',
            'subscription_ids.*' => 'exists:subscriptions,id',
            'send_from' => 'required|email'
        ];

        $validator = Validator($request->all(), $validationRules, [
            'send_email_date.required_if' => 'Send Email Date is required when Email Type is Scheduled',
            'send_email_time.required_if' => 'Send Email Time is required when Email Type is Scheduled',
            'days.required_if' => 'Days field is required when before or after is selected.',
            'before_or_after.required_if' => 'Before or after field is required when Email Type is Scheduled',
            'event.required_if' => 'Event field is required when Email Type is Transactional',
            'group_ids.*' => 'Selected Groups are invalid',
            'subscription_ids.*' => 'Selected Subscriptions are invalid',
            'send_from' => 'Sender email should be a valid email.'
        ]);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        if (in_array($request->get('event'),
            [
                EmailTemplate::EVENT['MEMBER_RESET_PASSWORD']
            ])) {
            return api_error(['error' => 'Only Super Admin Can Assign this']);
        }

        /** @var EmailTemplate $emailTemplate */
        $emailTemplate = $this->orgService->updateEmailTemplates($organization, $request->all());

        $message = 'Email Template added successfully';
        if (!empty ($request->get('id')))
            $message = 'Email Template updated successfully';
        return api_response($emailTemplate, null, $message);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|
     * @throws \Yajra\DataTables\Exceptions\Exception
     */
    public function getAllEmailTemplates(Request $request)
    {
        /** @var Organization $organization */
        $organization = $request->get(Organization::NAME);
        $result = \DataTables::collection($organization->emailTemplates()->with(['groups', 'subscriptions'])->get())->make();
        return api_response($result);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSendgridApi(Request $request)
    {
        /** @var Organization $organization */
        $organization = $request->get(Organization::NAME);
        return api_response($organization->sendgridSetting);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignEmailTemplatesToGroups(Request $request)
    {

        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'email_template_id' => 'required|exists:email_templates,id',
            'group_ids' => 'required|array'
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }


        /** @var EmailTemplate $emailTemplate */
        $emailTemplate = $organization->emailTemplates()->where('id', $request->get('email_template_id'))->first();
        if (empty($emailTemplate)) {
            return api_error(['error' => 'Unable to found email template']);
        }
        $emailTemplate->groups()->detach($emailTemplate->groups()->pluck('groups.id')->toArray()); //detach all groups from email templates.
        if (!empty($request->get('group_ids'))) {

            //attach present ids in the list.
            $groupIds = $organization->groups()->whereIn('id', $request->get('group_ids'))->pluck('id')->toArray();
            $emailTemplate->groups()->attach($groupIds);
        }

        return api_response([], null, 'Groups assigned successfully');
    }


    /**
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignEmailTemplatesToSubscriptions(Request $request)
    {

        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'email_template_id' => 'required|exists:email_templates,id',
            'subscription_ids' => 'required|array'
        ];

        $validator = Validator($request->all(), $validationRules);
        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        /** @var EmailTemplate $emailTemplate */
        $emailTemplate = $organization->emailTemplates()->where('id', $request->get('email_template_id'))->first();
        if (empty($emailTemplate)) {
            return api_error(['error' => 'Unable to found email template']);
        }

        $emailTemplate->subscriptions()->detach($emailTemplate->subscriptions()->pluck('subscriptions.id')->toArray()); //detach all subscriptions from email templates.

        if (!empty($request->get('subscription_ids'))) {

            //attach present ids in the list.
            $subscriptionIds = $organization->subscriptions()->whereIn('id', $request->get('subscription_ids'))->pluck('id')->toArray();
            $emailTemplate->subscriptions()->attach($subscriptionIds);
        }

        return api_response([], null, 'Subscriptions assigned successfully');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUntillSetting(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);
        $untillSetting = $this->orgService->addOrUpdateUntillSetting($organization, $request->all());
        return api_response($untillSetting);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUntillSetting(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);
        return api_response($organization->untillSetting);
    }

    public function updateOfficeUse(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
            'kiosk' => 'numeric'
        ];

        $validator = Validator($request->all(), $validationRules);

        if ($validator->fails()) {
            return api_error($validator->errors());
        }

        $officeUse = $this->orgService->updateOfficeUse($organization, $request->all());

        return api_response($officeUse);
    }

    public function getOfficeUse(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        return api_response($organization->officeUse);
    }

    /**
     * Update notification Api call.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateNotificationField(Request $request)
    {
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);

        /** @var MemberNotification $notification */
        $notification = $organization->memberNotifications()->where('member_notifications.id', $request->get('id'))->first();

        if (empty($notification)) {
            return api_error(['error' => 'Notification not found']);
        }

        /** @var MemberRepository $memberRepo */
        $memberRepo = new MemberRepository();

        $memberRepo->updateNotificationField($notification);

        $notification = $organization->memberNotifications()->where('member_notifications.id', $request->get('id'))
            ->with(['member' => function ($q) {
                $q->select('id', 'first_name', 'last_name', 'member_id');
            },
                'clickedByUser' => function ($q) {
                    $q->select('id','first_name');
                },
                'updatedByUser' => function ($q) {
                    $q->select('id','first_name');
                },
            ])->first();

        return api_response($notification, null, 'Member details updated successfully');
    }

    public function pushSavePointsToPos(Request $request, MemberRepository $memberRepository)
    {
        return api_response(null,null,'Please turn on pushing point from backend');
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);
        if($this->orgService->verifyUntillSetting($organization)){
            $organization->members()->orderBy('member_id', 'asc')->chunk(20, function ($members) use ($organization, $memberRepository) {
                $memberRepository->pushSavePointsToPos($organization, $members);
            });
        }else{
            return api_error(['error' => 'Invalid Pos Setting']);
        }

        return api_response(null,null,'Save points pushed to pos');
    }

    public function pushEmptySavePointsToPos(Request $request, MemberRepository $memberRepository)
    {
        return api_response(null,null,'Please turn on pushing point from backend');
        /** @var $organization Organization */
        $organization = $request->get(Organization::NAME);
        if($this->orgService->verifyUntillSetting($organization)){
            $organization->members()->orderBy('member_id', 'asc')->chunk(20, function ($members) use ($organization, $memberRepository) {
                $memberRepository->pushSavePointsToPos($organization, $members);
            });
        }else{
            return api_error(['error' => 'Invalid Pos Setting']);
        }

        return api_response(null,null,'Save points pushed to pos');
    }
}

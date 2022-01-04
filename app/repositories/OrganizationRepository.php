<?php
/**
 * Created by PhpStorm.
 * User: Feci
 * Date: 9/4/2017
 * Time: 1:17 PM
 */

namespace App\repositories;


use App\Address;
use App\base\AddressType;
use App\base\IStatus;
use App\EmailTemplate;
use App\Exceptions\ApiException;
use App\Exceptions\BrustSms\SmsListAlreadyExistsException;
use App\Group;
use App\Helpers\DropdownHelper;
use App\ListSentSms;
use App\Member;
use App\OfficeUse;
use App\Organization;
use App\OrganizationCardTemplate;
use App\OrganizationDetail;
use App\OrganizationKioskTemplate;
use App\OrganizationOption;
use App\OrganizationSetting;
use App\OrganizationSmsSetting;
use App\PaymentType;
use App\Plan;
use App\SendgridSetting;
use App\Services\BrustsmsService;
use App\SmsList;
use App\StripeSubscription;
use App\Subscription;
use App\UntillSetting;
use App\User;
use Carbon\Carbon;
use File;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\UnauthorizedException;
use Storage;

class OrganizationRepository
{
    public $organization_id;
    public $physical_address_id;
    public $postal_address_id;
    public $gst_number;
    public $starting_member;
    public $contact_name;
    public $contact_email;
    public $contact_number;
    public $industry;
    public $physical_address;
    public $postal_address;
    public $smsService;

    /* @var  $planRepo PlanRepository */
    public $planRepo;

    public function __construct()
    {
        $this->physical_address = new Address(['address_type_id' => AddressType::PHYSICAL_ADDRESS]);
        $this->postal_address = new Address(['address_type_id' => AddressType::POSTAL_ADDRESS]);
        $this->smsService = new BrustsmsService();
        $this->planRepo = new PlanRepository();
    }

    /**
     * Save Details of Organization in organization_details table.
     *
     * @param Request $request
     * @return mixed|static
     */
    public function saveDetails(Request $request)
    {
        $organization = Organization::find($request->organization_id);
        if (empty($organization)) {
            throw new UnauthorizedException('You Are Not Authorize To Change Details Of This Organization');
        }
        $organizationDetails = OrganizationDetail::where(['organization_id' => $request->organization_id])->first();

        if (empty($organizationDetails)) {  //if not organization details create new instance.
            $organizationDetails = new OrganizationDetail();
        }

        if (isset($organizationDetails->physical_address_id)) { //if physical address is set then use that
            $physical_address = Address::find($organizationDetails->physical_address_id);
        } else {
            $physical_address = new Address();
        }

        if (isset($organizationDetails->postal_address_id)) {   //if postal address is set then use that
            $postalAddress = Address::find($organizationDetails->physical_address_id);
        } else {
            $postalAddress = new Address();
        }

        if ($request->postal_country >= 1) {

            $postalAddress->country_id = $request->postal_country;
            $postalAddress->address1 = $request->postal_first_address;
            $postalAddress->address2 = $request->postal_second_address;
            $postalAddress->suburb = $request->postal_suburb;
            $postalAddress->city = $request->postal_city;
            $postalAddress->region = $request->postal_region;
            $postalAddress->status_id = IStatus::ACTIVE;
            $postalAddress->postal_code = $request->postal_postal_code;
            $postalAddress->item_id = $request->organization_id;
            $postalAddress->address_type_id = AddressType::POSTAL_ADDRESS;
            $postalAddress->item_type_id = AddressType::ORGANIZATION;
            $postalAddress->save();
        }

        $physical_address->country_id = $request->physical_country;
        $physical_address->address1 = $request->physical_first_address;
        $physical_address->address2 = $request->physical_second_address;
        $physical_address->suburb = $request->physical_suburb;
        $physical_address->city = $request->physical_city;
        $physical_address->region = $request->physical_region;
        $physical_address->latitude = $request->physical_latitude;
        $physical_address->longitude = $request->physical_longitude;
        $physical_address->status_id = IStatus::ACTIVE;
        $physical_address->postal_code = $request->physical_postal_code;
        $physical_address->item_id = $request->organization_id;
        $physical_address->address_type_id = AddressType::POSTAL_ADDRESS;
        $physical_address->item_type_id = AddressType::ORGANIZATION;
        $physical_address->save();


//        $organizationDetails->physical_address_id = $physical_address->id;
        $organizationDetails->organization_id = $request->organization_id;
        $organizationDetails->contact_name = $request->contact_name;
        $organizationDetails->contact_email = $organization->owner->email;
        $organizationDetails->contact_phone = $request->contact_phone;
        $organizationDetails->office_phone = $request->office_phone;
        $organizationDetails->account_no = $organization->id;
        $organizationDetails->industry = $request->industry;
        $organizationDetails->gst_number = $request->gst_number;
        $organizationDetails->tax_rate = $request->get('tax_rate');
        $organizationDetails->pos_vendor = $request->get('pos_vendor');
        $organizationDetails->starting_member = $request->starting_member;
        $organizationDetails->starting_receipt = $request->starting_receipt;
        $organizationDetails->next_member = (!empty($request->next_member)) ? $request->next_member : 1;
        $organizationDetails->postal_address_id = $postalAddress->id ?: null;
        $organizationDetails->physical_address_id = $physical_address->id ?: null;
        $organizationDetails->save();


        $this->activateOrg($request->organization_id);
        return $organization;
    }

    /**
     * Activate Organization (as details are filled up)
     *
     * @param $id
     */
    public function activateOrg($id)
    {
        $org = Organization::find($id);
        if ($org) {
            $org->status = IStatus::ACTIVE;
            $org->update();
        }
    }

    /**
     * If user have any pending organization which haven't filled up,
     * this will send that first org every time if there
     *
     * @param User $user
     * @return mixed| Organization | null
     */
    public function getPendingOrg(User $user)
    {
        return Organization::where('user_id', $user->id)->whereStatus(IStatus::INACTIVE)->orderBy('id', 'desc')->first();
    }

    /**
     * The list of organizations against a user.
     *
     * @param $user_id
     * @return mixed | array['organization_name, role_name']
     */
    public function getAllOrg($user_id)
    {
        $organization = DB::table('organizations');
        $organization->leftJoin('stripe_subscriptions', 'stripe_subscriptions.organization_id', 'organizations.id');
        $organization->join('role_user', 'role_user.organization_id', 'organizations.id');
        $organization->join('roles', 'role_user.role_id', 'roles.id');
        $organization->where('role_user.user_id', $user_id);
        $organization->where('role_user.status', IStatus::ACTIVE);
//        $organization->with('details.physicalAddress','details.postalAddress');
        $organization->select(['organizations.name', 'roles.name as role_name', 'organizations.id', 'organizations.plan_expiry']);
        $organization->addSelect(['stripe_subscriptions.current_period_start', 'stripe_subscriptions.current_period_end']);
        $result = $organization->get();
        return $result;
    }

    /**
     * De-attaching a user from all organizations
     *
     * @param $user_id
     * @return bool
     */
    public function unsetCurrentAll($user_id)
    {
        $org = DB::table('role_user');
        $org->join('organizations', 'role_user.organization_id', 'organizations.id');
        $org->where('role_user.user_id', $user_id);
        $org->select(['organizations.*']);
        $listOfDetails = $org->update(['role_user.current' => null]);
        return $listOfDetails;
    }


    public function unsetOrganization($user_id, $organization_id)
    {

        $org = DB::table('role_user');
        $org->join('organizations', 'role_user.organization_id', 'organizations.id');
        $org->where('role_user.user_id', $user_id);
        $org->where('role_user.organization_id', $organization_id);
        $org->select(['organizations.*']);

        $listOfDetails = $org->update(['role_user.current' => null]);

        return $listOfDetails;
    }

    /**
     * Set an Organization as current organization of user.
     *
     * @param $user_id
     * @param $organization_id
     * @return Organization
     * @internal param Organization $org
     */
    public function setCurrentOrg($user_id, $organization_id)
    {
        $this->unsetCurrentAll($user_id);

        $org = DB::table('role_user');
        $org->join('organizations', 'role_user.organization_id', 'organizations.id');
        $org->where('role_user.user_id', $user_id);
//        $org->where('role_user.status', IStatus::ACTIVE);
        $org->where('role_user.organization_id', $organization_id);
        $result = $org->update(['role_user.current' => IStatus::ACTIVE]);
        return $result;
    }

    /**
     * Get Organization by Organization and User Id .
     *
     * @param $org_id
     * @param $user_id
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function findByUserId($org_id, $user_id)
    {
        $org = Organization::where([
            'user_id' => $user_id,
            'id' => $org_id,
        ])->first();
        return $org;
    }

    /**
     * Depricated ________________ Get Current Organization of a user.
     *
     * @param $user_id
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function getCurrentOrg($user_id)
    {
        $org = Organization::where([
            'user_id' => $user_id,
            'current' => IStatus::ACTIVE,
            'status' => IStatus::ACTIVE,
        ])->with('details', 'details.physicalAddress', 'details.postalAddress')->first();
        return $org;
    }

    /**
     * Return the all organizations with their details.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function all()
    {
        return Organization::with('details', 'details.physicalAddress', 'details.postalAddress')->get();
    }

    public function getAdminCurrentOrg($user_id)
    {
        $org = DB::table('role_user');
        $org->join('organizations', 'role_user.organization_id', 'organizations.id');
        $org->where('role_user.user_id', $user_id);
        $org->select(['organizations.*']);
        $result = $org->first();

        return $result;
    }


    public function setAdminCurrentOrg($user_id, $organization_id)
    {
        $attributes = [
            'organization_id' => $organization_id,
            'current' => IStatus::ACTIVE,
        ];
        $org = User::find($user_id)->roles()->updateExistingPivot(1, $attributes);
        $result = Organization::find($organization_id);
        return $result;
    }


    /**
     * Current organization against any user member|manager|office
     *
     * @param $user_id
     *
     * @return mixed, Organization
     */
    public function findCurrentOrganization($user_id)
    {
        $org = DB::table('role_user');
        $org->join('organizations', 'role_user.organization_id', 'organizations.id');
        $org->where('role_user.user_id', $user_id);
        $org->where('role_user.current', IStatus::ACTIVE);
        $org->select(['organizations.*']);
        $result = $org->first();
        return $result;
    }


    /**
     * Returns the organization against any user, member|office via role_user table.
     * @param $user_id
     * @param $organization_id
     * @return mixed
     */
    public function getByIdWithRole($user_id, $organization_id)
    {
        $org = DB::table('role_user');
        $org->join('organizations', 'role_user.organization_id', 'organizations.id');
        $org->where('role_user.user_id', $user_id);
        $org->where('role_user.organization_id', $organization_id);
        $org->select(['organizations.*']);
        $result = $org->first();

        return $result;
    }

    public function findOrgWithRoleId($user_id, $organization_id, $role_id, $status = null)
    {
        $org = DB::table('role_user');
        $org->join('organizations', 'role_user.organization_id', 'organizations.id');
        $org->where('role_user.user_id', $user_id);
        if (!empty($status)) {
            $org->where('role_user.current', $status);
        }
        $org->where('role_user.organization_id', $organization_id);
        $org->where('role_user.role_id', $role_id);
        $org->select(['organizations.*']);
        $result = $org->first();
        return $result;
    }

    public function getUsers($organization_id)
    {

        $query = DB::table('role_user');
        $query->join('organizations', 'role_user.organization_id', 'organizations.id');
        $query->join('users', 'role_user.user_id', 'users.id');
        $query->join('roles', 'role_user.role_id', 'roles.id');
        $query->leftJoin('user_last_logins',function($join){
            $join->on('user_last_logins.organization_id', 'organizations.id');
            $join->on('user_last_logins.user_id', 'users.id');
        });
        $query->select(['user_last_logins.*','users.*',  'roles.name as role_name', 'role_user.role_id as role_id', 'role_user.status as status']);
        $query->where('role_user.organization_id', $organization_id);
//        $query->where('role_user.status', IStatus::ACTIVE);
        $result = $query->get();
        return $result;
    }

    public function checkOrgAgainstUser($organization_id, $user_id)
    {
        $query = DB::table('role_user');
        $query->where('organization_id', $organization_id);
        $query->where('status', IStatus::ACTIVE);
        $query->where('user_id', $user_id);
        $result = $query->first();
        return $result;
    }

    public function enableOrganization($organization_id, $user_id)
    {
        $org = DB::table('role_user');
        $org->join('organizations', 'role_user.organization_id', 'organizations.id');
        $org->where('role_user.user_id', $user_id);
        $org->where('role_user.organization_id', $organization_id);
        $details = $org->update(['role_user.status' => 1]);

//            User::find($user_id)->roles()->wherePivot('organization_id' , $organization_id)->update(['role_user.status']);
        return $details;
    }

    public function disableOrganization($organization_id, $user_id)
    {
        $org = DB::table('role_user');
        $org->join('organizations', 'role_user.organization_id', 'organizations.id');
        $org->where('role_user.user_id', $user_id);
        $org->where('role_user.organization_id', $organization_id);
        $details = $org->update(['role_user.current' => null, 'role_user.status' => IStatus::INACTIVE]);
//            User::find($user_id)->roles()->wherePivot('organization_id' , $organization_id)->update(['role_user.status']);
        return $details;
    }

    /**
     * @return array
     */
    public static function AuthorizedFields()
    {
        return [
            'contact_name', 'contact_email', 'contact_phone', 'office_phone', 'industry', 'gst_number', 'next_member', 'tax_rate', 'pos_vendor', 'income_tax'
        ];
    }

    /**
     * @param OrganizationDetail $organizationDetail
     * @param $fieldname
     * @param $fieldValue
     * @return OrganizationDetail
     */
    public function saveField(OrganizationDetail $organizationDetail, $fieldname, $fieldValue)
    {
        $organizationDetail->$fieldname = $fieldValue;
        $organizationDetail->update();
        return $organizationDetail;
    }

    public function saveOptionField(OrganizationOption $model, $fieldname, $fieldValue)
    {
        $model->$fieldname = $fieldValue;
        $model->update();
        return $model;
    }

    public function getById($id)
    {
        return Organization::where('id', $id)
            ->with(['details', 'details.physicalAddress', 'details.postalAddress', 'owner', 'options', 'paymentTypes', 'smsSetting','officeUse'])
            ->first();
    }

    public function getCurrentMonthBirthdayMembersCount($organization = null)
    {
        if (empty($organization)) {
            return 0;
        }

        $data = $this->currentMonthBirthdayMembers($organization);
        return count($data);
    }

    public function currentMonthBirthdayMembers($organization)
    {
        if (empty($organization->id)) {
            return [];
        }
        $currentMonth = date('m');
        $data = DB::table("members")
            ->where('organization_id', $organization->id)
            ->whereRaw('MONTH(date_of_birth) = ?', [$currentMonth])
            ->get();
        return $data;
    }

    public function addPaymentType(Organization $organization, $data)
    {
        if (!empty(array_get($data, 'id'))) {
            $paymentType = $organization->paymentTypes()->find(array_get($data, 'id'));
        } else {
            $paymentType = new PaymentType();
        }
        if ($paymentType == null) return [];

        $paymentType->name = array_get($data, 'name');
        $paymentType->organization_id = $organization->id;
        $paymentType->save();
        return $organization->paymentTypes;
    }

    public function updateSmsField(OrganizationSmsSetting $smsSetting, $field, $value)
    {
        $smsSetting->$field = $value;
        $smsSetting->save();
        $smsSetting->refresh();
        return $smsSetting;
    }

    /**
     * @param $name
     * @param Group|null $group
     * @return SmsList
     */
    public function addSmsList($name, Group $group = null)
    {
        $this->smsService->setup($group->organization);

        try {
            $list = $this->smsService->createList($name, []);
        } catch (SmsListAlreadyExistsException $exception) {
            $listResponse = $this->smsService->getLists();
            $brustSmsLists = $listResponse->lists;
            foreach ($brustSmsLists as $brustSmsList) {
                if ($brustSmsList->name == $group->name) {
                    $list = $brustSmsList;
                }
            }
        }

        /* @var  $smsList SmsList */
        if (!empty($list)) {
            $smsList = new SmsList();
            $smsList->name = $list->name;
            $smsList->group_id = array_get($group, 'id');
            $smsList->ref_id = $list->id;
            $smsList->organization_id = array_get($group, 'organization_id');
            $smsList->save();
            return $smsList;
        }
        return null;
    }

    /**
     * will save and update kiosk template.
     *
     * @param Organization $organization
     * @param $data
     * @return \Illuminate\Database\Eloquent\Model
     * @throws ApiException
     */
    public function saveKioskTemplate(Organization $organization, $data)
    {
        if (empty(array_get($data, 'template_no'))) {
            throw  new ApiException(null, ['error' => 'Template number is required']);
        }

        $kioskTemplate = OrganizationKioskTemplate::updateOrCreate(
            ['organization_id' => array_get($data, 'organization_id'), 'template_no' => array_get($data, 'template_no')],
            $data
        );

        $logoToUpload = array_get($data, 'logo');
        if (!empty($logoToUpload)) {
            $name = $logoToUpload->getClientOriginalName();
            $name = md5($name) . '.' . $logoToUpload->getClientOriginalExtension();
            $path = '/kiosk-logo/' . $name;
            Storage::put($path, File::get($logoToUpload->getRealPath()));
            $url = Storage::disk('local')->url($path);
            $kioskTemplate->logo = $url;
            $kioskTemplate->update();
        }

        return $kioskTemplate;
    }

    public function addBulkContacts(Organization $organization, Group $group, $name, $url)
    {
        $this->smsService->setup($organization);
//        $countryCode = !empty($organization->smsSetting->country_code) ? $organization->smsSetting->country_code:'+64';
        $response = $this->smsService->addBulkContacts($name, $url, null, null);
        $smsList = new SmsList();
        $smsList->name = $group->name;
        $smsList->group_id = array_get($group, 'id');
        $smsList->ref_id = $response->list_id;
        $smsList->organization_id = array_get($organization, 'id');
        $smsList->save();

        return $smsList;
    }

    /**
     * SendSmsToList To Send sms to list.
     * @param $organization
     * @param $list
     * @param $message
     * @return ListSentSms
     * @throws ApiException
     */
    public function sendSmsToList(Organization $organization, SmsList $list, $message)
    {
        $this->smsService->setup($organization);
        $response = $this->smsService->sendSmsToList($list->ref_id, $message);

        /* @var $listSentSms ListSentSms */
        $listSentSms = new ListSentSms();
        $listSentSms->message = $message;
        $listSentSms->organization_id = $organization->id;
        $listSentSms->sms_list_id = $list->id;
        $listSentSms->cost = $response->cost;
        $listSentSms->recipients = $response->recipients;
        $listSentSms->sms = $response->sms;
        $listSentSms->delivered = $response->delivery_stats->delivered;
        $listSentSms->pending = $response->delivery_stats->pending;
        $listSentSms->bounced = $response->delivery_stats->bounced;
        $listSentSms->responses = $response->delivery_stats->responses;
        $listSentSms->optouts = $response->delivery_stats->optouts;
        $listSentSms->save();
        return $listSentSms;
    }

    public function getSmsDetails(Organization $organization, $messageId)
    {
        $this->smsService->setup($organization);
        $response = $this->smsService->getSmsDetails($messageId);
    }

    /**
     * Return the delivery status of a message against a number.
     *
     * @param Organization $organization
     * @param $messageId
     * @param $number
     * @throws ApiException
     */
    public function getSmsDeliveryStatus(Organization $organization, $messageId, $number)
    {
        $this->smsService->setup($organization);
        $response = $this->smsService->getDeliveryStatus($messageId, $number);
    }

    /**
     * This will return all the message details in which cost, delivery_stats , recipients will be available
     *
     * @param Organization $organization
     * @param $messageId
     * @throws ApiException
     */
    public function getSmsSent(Organization $organization, $messageId)
    {
        $this->smsService->setup($organization);
        $response = $this->smsService->getSmsSent($messageId);
    }

    /**
     * @param Organization $organization
     * @param $listId
     * @param $membersToUpload
     * @return bool
     * @throws ApiException
     */
    public function checkSmsListReady(Organization $organization, $listId, $membersToUpload)
    {
        $this->smsService->setup($organization);
        $response = $this->smsService->getList($listId);
        if (!empty($response->members_active) && $response->members_active == $membersToUpload) {
            return true;
        }
        return false;
    }

    public function getSmsBalance(Organization $organization)
    {
        $this->smsService->setup($organization);
        $response = $this->smsService->getBalance();

        /* @var $smsSetting OrganizationSmsSetting */
        $smsSetting = $organization->smsSetting;
        $smsSetting->sms_balance = (!empty($response->balance)) ? $response->balance : $smsSetting->sms_balance;
        $smsSetting->save();
        return $smsSetting->sms_balance;
        //this response have IPay user in balance, need to discuss with client.
    }

    public function createStatusGroups(Organization $organization)
    {
        $statusList = DropdownHelper::memberStatusList();
        foreach ($statusList as $status) {
            $newGroup = new Group();
            $newGroup->organization_id = $organization->id;
            $newGroup->status = IStatus::ACTIVE;
            $newGroup->name = $status;
            $newGroup->type = Group::TYPE['STATUS'];
            $newGroup->is_status_group = IStatus::ACTIVE;
            $newGroup->save();
        }
    }

    public function updateOrganizationSettings(Organization $organization, $data = [])
    {
        $organizationSettings = $organization->organizationSettings;
        if (empty($organizationSettings)) {
            $organizationSettings = new OrganizationSetting();
            $organizationSettings->organization_id = $organization->id;
        }
        $organizationSettings->subscription_start_date = array_get($data, 'subscription_start_date', $organizationSettings->subscription_start_date);
        $organizationSettings->save();
        return $organizationSettings;
    }

    public function setPlan(Organization $organization, $planId)
    {
        //todo stripe payment update with payment details.

        //organization planId saving | expiry date will be set on the callback from payment gateway.
        $organization->plan_id = $planId;
        if ($planId == Plan::TRAIL) {
            $organization->plan_payment_status = IStatus::ACTIVE;
        } else {
            $organization->plan_payment_status = IStatus::INACTIVE;
        }
        $organization->save();

        //saving operational details (feature details) for organization
        $plan = Plan::find($planId);
        if ($plan) {
            $features = $plan->features;
            foreach ($features as $feature) {
                $limit = $feature->featurePlanPivot->limit;
                $this->planRepo->addNewUserFeature($organization, $organization->owner, $feature, $limit);
            }
        }

        //assigning add-ons
    }

    /**
     * This function will setup everything for newlycreated things in system.
     * @param Organization $organization
     */
    public function setupNewOrganization(Organization $organization)
    {
        $this->createStatusGroups($organization);   //creating all statuses as group to attach or detach the members. ( pre-setup groups )
        $this->addPaymentType($organization, ['name' => PaymentType::TYPE['CASH']]);     // creating default payment type to get payments from members. The Cash Payment Type
    }

    /**
     * This function will match the stripe api returned subscription with the system subscriptions for that organization
     * and update the based on the organization id. one organization can have only one stripe subscription at one time.
     *
     * @param Organization $organization
     * @param array $subscription
     * @return StripeSubscription|mixed
     * @throws ApiException
     */
    public function addStripeSubscription(Organization $organization, array $subscription)
    {
        $stripeSubscription = $organization->stripeSubscription;
        if (!$stripeSubscription) {   //if already no subscription for that organization
            $stripeSubscription = new StripeSubscription();
            $stripeSubscription->organization_id = $organization->id;
        }

        if (!empty(array_get($subscription, 'plan'))) {
            $stripePlan = array_get($subscription, 'plan');
            $plan = Plan::whereRefId(array_get($stripePlan, 'id'))->first();
            if ($plan) {
                $planId = array_get($plan, 'id');
            }
        }
        if (empty($planId)) {
            $plan = $organization->plan;
            $planId = array_get($plan, 'id');
        }

        //region Throw exception if plan not found.
        if (empty($planId)) {
            throw new ApiException(null, ['error' => 'Unable to find system plan']);
        }
        //endregion

        $stripeSubscription->plan_id = $planId;
        $stripeSubscription->customer = array_get($subscription, 'customer');
        $stripeSubscription->current_period_end = date('Y-m-d h:s:i', array_get($subscription, 'current_period_end'));
        $stripeSubscription->current_period_start = date('Y-m-d h:s:i', array_get($subscription, 'current_period_start'));
        $stripeSubscription->days_until_due = array_get($subscription, 'days_until_due');
        $stripeSubscription->default_source = array_get($subscription, 'default_source');
        $stripeSubscription->discount = array_get($subscription, 'discount');
        $stripeSubscription->ended_at = array_get($subscription, 'ended_at');
        $stripeSubscription->quantity = array_get($subscription, 'quantity');
        $stripeSubscription->status = array_get($subscription, 'status');
        $stripeSubscription->tax_percent = array_get($subscription, 'tax_percent');
        $stripeSubscription->ref_id = array_get($subscription, 'id');
        $stripeSubscription->save();

        if ($stripeSubscription->status == IStatus::STRIPE_ACTIVE_STATUS && array_get($subscription, 'current_period_end') > strtotime('now')) {

            $organization->plan_payment_status = IStatus::ACTIVE;
//            $organization->plan_expiry = date('d-m-Y h:s:i',array_get($subscription,'current_period_end'));
            $organization->plan_expiry = Carbon::createFromTimestamp(array_get($subscription, 'current_period_end'));
            $organization->save();
        }

        return $stripeSubscription;
    }

    /**
     * @param Organization $organization
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveDraws(Organization $organization)
    {
        $draws = $organization
            ->draws()
            ->where('duration_start', '<=', Carbon::now())
             ->where('duration_finish', '>=', Carbon::now())
            ->where('status', IStatus::ACTIVE)
            ->get();
        $filteredDaysDraws = new Collection();
        $todayDay  = Carbon::now();
        $todayDay = $todayDay->dayName;
        foreach ($draws as $item) {
            $drawDays = $item->draw_days;
            if(!empty($drawDays)){
                if($drawDays->draw_days_all){
                    $filteredDaysDraws->push($item);
                }else if ($drawDays->draw_days_all) {
                    $filteredDaysDraws->push($item);
                }else if ($drawDays->draw_days_mon && $todayDay == 'Monday') {
                    $filteredDaysDraws->push($item);
                }else if ($drawDays->draw_days_tue && $todayDay == 'Tuesday') {
                    $filteredDaysDraws->push($item);
                }else if ($drawDays->draw_days_wed && $todayDay == 'Wednesday') {
                    $filteredDaysDraws->push($item);
                }else if ($drawDays->draw_days_thu && $todayDay == 'Thursday') {
                    $filteredDaysDraws->push($item);
                }else if ($drawDays->draw_days_fri && $todayDay == 'Friday') {
                    $filteredDaysDraws->push($item);
                }else if ($drawDays->draw_days_sat && $todayDay == 'Saturday') {
                    $filteredDaysDraws->push($item);
                }else if ($drawDays->draw_days_sun && $todayDay == 'Sunday') {
                    $filteredDaysDraws->push($item);
                }
            }
        }
        return $filteredDaysDraws ?? [];
    }

    /**
     * @param User $user
     * @return bool
     * @throws ApiException
     */
    public function checkStripeSubscriptionPaymentInfo(User $user)
    {
        /* @var $currentOrganization Organization */
        $currentOrganization = $this->findCurrentOrganization($user->id);

        if (!empty($currentOrganization)) {
            $organization = Organization::find($currentOrganization->id);
            if ($organization-> id != 15550 && $organization->plan_id != Plan::TRAIL && $organization->plan_payment_status == IStatus::INACTIVE) {
                return false;
            }
            return true;
        } else {
            throw new ApiException(null, ['error' => 'No Current Organization']);
        }
    }

    /**
     * @param SendgridSetting $sendgridSetting
     * @param array $data
     * @return SendgridSetting|mixed
     */
    public function addSendgridApiKey(SendgridSetting $sendgridSetting, $data = [])
    {
        $sendgridSetting->api_key = array_get($data, 'sendgrid_api_key');
        $sendgridSetting->save();
        return $sendgridSetting;
    }

    /**
     * @param Organization $organization
     * @param array $data
     * @return EmailTemplate
     */
    public function updateEmailTemplates(Organization $organization, $data = [])
    {
        $id = array_get($data, 'id');
        if($id){
            /** @var EmailTemplate $emailTemplate */
            $emailTemplate = $organization->emailTemplates()->firstOrNew(['id' => $id]);
        }else{
            $emailTemplate = new EmailTemplate();
        }
        $emailTemplate->template_id = array_get($data, 'template_id', $emailTemplate->template_id);
        $emailTemplate->send_email_date = array_get($data, 'send_email_date', $emailTemplate->send_email_date);
        $emailTemplate->send_from = array_get($data, 'send_from', $emailTemplate->send_from);
        $emailTemplate->before_or_after = array_get($data, 'before_or_after', $emailTemplate->before_or_after);
        $emailTemplate->days = array_get($data, 'days', $emailTemplate->days);
        $emailTemplate->send_email_time = array_get($data, 'send_email_time', $emailTemplate->send_email_time);
        $emailTemplate->email_type = array_get($data, 'email_type', $emailTemplate->email_type);
        $emailTemplate->email_group = array_get($data, 'email_group', $emailTemplate->email_group);
        $emailTemplate->email_name = array_get($data, 'email_name', $emailTemplate->email_name);
        $emailTemplate->event = array_get($data, 'event', $emailTemplate->event);
        $emailTemplate->organization_id = $organization->id;
        $emailTemplate->save();

        if(!empty(array_get($data,'subscription_ids'))){
            $emailTemplate->subscriptions()->sync(array_get($data,'subscription_ids'));
        }

        if(!empty(array_get($data,'group_ids'))){
            $emailTemplate->groups()->sync(array_get($data,'group_ids'));
        }

        return EmailTemplate::whereId($emailTemplate->id)->with('subscriptions','groups')->first();
    }

    /**
     * @param Organization $organization
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function addOrUpdateUntillSetting(Organization $organization, $data = [])
    {
        $untillSetting = $organization->untillSetting;
        if (empty($untillSetting)) $untillSetting = new UntillSetting();
        $untillSetting->url = array_get($data, 'url', array_get($untillSetting, 'url', null));
        $untillSetting->password = array_get($data, 'password', array_get($untillSetting, 'password', null));
        $untillSetting->port = array_get($data, 'port', array_get($untillSetting, 'port', null));
        $untillSetting->username = array_get($data, 'username', array_get($untillSetting, 'username', null));
        $untillSetting->organization_id = $organization->id;
        $untillSetting->save();
        return $untillSetting;
    }

    public function verifyUntillSetting(Organization $organization){
        $untillSetting = $organization->untillSetting;
        if(empty($untillSetting)){
            return false;
        }else{
            if(empty($untillSetting->username) || empty($untillSetting->password) || empty($untillSetting->url) || empty($untillSetting->port)){
                return false;
            }else{
                return true;
            }
        }
    }

    /**
     * @param Organization $organization
     * @param $data
     * @return OfficeUse|\Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function updateOfficeUse(Organization $organization, $data)
    {
        $officeUse = $organization->officeUse;

        if(empty($officeUse)){
            $officeUse = new OfficeUse();
            $officeUse->organization_id = $organization->id;
        }

        $officeUse->kiosk = array_get($data,'kiosk',array_get($officeUse,'kiosk'));
        $officeUse->save();
        return $officeUse;
    }

    public function assignTemplate(Member $member, $cardTemplate)
    {
        try{
            /** @var MemberRepository $memberRepo */
            $memberRepo = new MemberRepository();
            $member->organization_card_template_id = $cardTemplate->id;
            $member->member_id_card = $memberRepo->generateMemberCard($cardTemplate, $member);
            $member->save();
            return true;
        }catch (\Exception $exception){
            \Log::info($exception->getMessage());
            return false;
        }
    }
}


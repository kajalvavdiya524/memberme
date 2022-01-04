<?php
/**
 * Created by PhpStorm.
 * User: Faisal
 * Date: 11/28/2017
 * Time: 11:54 PM
 */

namespace App\repositories;

use App\Address;
use App\base\AddressType;
use App\base\IRecordType;
use App\base\IResponseCode;
use App\base\IStatus;
use App\ChangeLog;
use App\Country;
use App\Draw;
use App\DrawEntry;
use App\EmailTemplate;
use App\Exceptions\ApiException;
use App\Exceptions\BrustSms\MemberAlreadyInList;
use App\Exceptions\BrustSms\MemberNotExistInListException;
use App\Group;
use App\GroupMember;
use App\Helpers\ApiHelper;
use App\Helpers\DropdownHelper;
use App\Mail\AddMemberEmail;
use App\Mail\Member\SendEmailChangeEamil;
use App\Mail\Member\SendMemberVerificationEmail;
use App\Mail\Member\SendResetEmailForMembers;
use App\Mail\MemberAddNotification;
use App\Member;
use App\MemberCoffeeCard;
use App\MemberCoffeeCardLog;
use App\MemberCoffeeCardReward;
use App\MemberDirectPaymentDetails;
use App\MemberEducation;
use App\MemberEmployment;
use App\MemberNotification;
use App\MemberOther;
use App\MemberProfile;
use App\MemberViewLog;
use App\Note;
use App\Organization;
use App\OrganizationCardTemplate;
use App\OrganizationDetail;
use App\OrganizationSetting;
use App\Record;
use App\SendgridSetting;
use App\Services\BrustsmsService;
use App\Services\Untill\UntillService;
use App\SmsList;
use App\SmsListMember;
use App\Subscription;
use Carbon\Carbon;
use Cassandra\Rows;
use DateTime;
use DB;
use Doctrine\DBAL\Query\QueryBuilder;
use Dompdf\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Constraint;
use Intervention\Image\Image;
use League\Glide\Api\Api;
use Mail;
use PharIo\Manifest\Email;
use phpDocumentor\Reflection\DocBlock\Tags\Factory\Strategy;

class MemberRepository
{
    /* @var $member Member */
    public $member;

    /* @var $smsService BrustsmsService */
    public $smsService;

    public function __construct()
    {
        $this->member = new Member();
        $this->smsService = new BrustsmsService();
    }

    /**
     * Adding member to the organization this will include these steps.
     * 1. Member birthday formation.
     * 2. Member Id generation for member if member isn't a contact - 2.1 Adding type to the member.
     * 3. Creating new member && assigning joining date if member isn't a contact
     * 4. Attaching subscription if member isn't a contact
     * 5. Inserting new member_other
     * 6.Saving next member Id to org Details
     * 7. Sending email and setting password and Api key if member isn't a contact
     * 8. Sending email for verification. Sign-up Case.
     * 9. Sending newly created member to the Pos
     * 10. If already a member then assign its validate id to new member.
     * 11. Assign first background Card Template and Generate its member card if not a contact.
     * @param $data
     * @param bool $sendEmail
     * @param bool $sendAllEmails
     * @param bool $sendVerificationEmail
     * @param bool $isContact
     * @return mixed
     * @throws ApiException
     */
    public function addMember($data, $sendEmail = true, $sendAllEmails = true, $sendVerificationEmail = false, $isContact = false)
    {
        //region 1. Resolving Date of birth - formatting
        if (isset($data['date_of_birth']) && !empty($data['date_of_birth'])) {
            $date_of_birth = $data['date_of_birth'];
            $dateObj = str_replace('/', '-', $date_of_birth);
            if (empty($dateObj) || !$dateObj) {
                $dateObj = new Carbon($date_of_birth);
            }

            $date = date('Y-m-d', strtotime($dateObj));
            $data['date_of_birth'] = $date;
        }
        //endregion

        //region 2. Member Id generation for member if member isn't a contact

        if (!$isContact) {        //if the current member is not a contact then assign a member id to him.
            if (isset($data['organization_id'])) {
                if (empty($data['member_id'])) {
                    $data['member_id'] = $this->generateNextMemberNo($data['organization_id']); // get next member number in org details  or 1
                    $data['member_id'] = $this->findUnmatchedNumber($data['member_id'], $data['organization_id']);
                }
            }

            $data['verify'] = IStatus::INACTIVE;
            $data['status'] = IStatus::PENDING_NEW;
        }
        //endregion

        //region 2.1 Adding type to the member.
        if ($isContact) {
            $data['type'] = Member::TYPE['CONTACT'];
        } else {
            $data['type'] = Member::TYPE['MEMBER'];
        }
        //endregion

        //region 3. Creating new member && assigning joining date if member isn't a contact
        $newMember = Member::create($data);

        if (isset($data['join_date'])) {
            $dateObject = new Carbon(str_replace('/', '-', $data['join_date']));
            $newMember->joining_date = date('Y-m-d H:i:s', $dateObject->getTimestamp());
        }
        if (empty($newMember->joining_date)) {
            $newMember->joining_date = Carbon::now();
        }

        // if this is not a contact then add the joining date to the member.
        if (!$isContact) {
            $newMember->save();
        }

        //endregion

        //region 4. Attaching subscription if member isn't a contact
        $organization = Organization::find($data['organization_id']);
        //if subscription is being assigned from the form.
        if (!empty($data['subscription'])) {
            $newMember->subscription_id = $data['subscription'];
            $newMember->save();
            $this->changeStatus($newMember, IStatus::PENDING_NEW);
        } else {
            if (!empty($organization))
                $subscription = $organization->subscriptions()
                    ->where('auto_assign', IStatus::ACTIVE)
                    ->where('status', IStatus::ACTIVE)
                    ->first();
            if (!empty($subscription) && !$isContact) {
                $newMember->subscription_id = $subscription->id;
                $newMember->save();
                $this->changeStatus($newMember, IStatus::PENDING_NEW);
            }
        }
        //endregion

        //region 5. Inserting new member_other
        if (!empty($newMember) && !$isContact) {
            \App\MemberOther::firstOrCreate(['member_id' => $newMember->id]);
        }
        //endregion

        //region 6.Saving next member Id to org Details
        if(!$isContact){
            $this->saveNextMember($data['organization_id']);    //saving next member number into the org details.
        }
        //endregion

        //region 7. Sending email and setting password and Api key if member isn't a contact

        $emailSent = false;
        $this->addMemberProfile($newMember, $data); //add member profile

        if (!empty(array_get($data, 'email')) && !$isContact) {

            try {
                if ($sendEmail) {
                    $memberWithSameEmail = Member::whereEmail(array_get($data, 'email'))->count();
                    if ($memberWithSameEmail == 1) {
                        $password = Str::random(8);
                        $newMember->password = md5($password);
                        $newMember->api_token = Str::random(60);
                        $newMember->verify_token = Str::random(60);
                        $newMember->update();
//                        Mail::to(array_get($data, 'email'))->send(new AddMemberEmail($organization, $newMember, $password);
//                        $this->sendNewMemberEmail(array_get($data,'email'),$organization,$newMember,$password);
                        $emailSent = true;
                        $newMember->password_sent_date_time = Carbon::now();
                        $newMember->update();
                    } else {
                        $emailSent = true;
//                        Mail::to(array_get($data, 'email'))->send(new MemberAddNotification($organization, $newMember));
                    }
                } else {
                    $newMember->password = md5(array_get($data, 'password'));
                    $newMember->api_token = Str::random(60);
                    $newMember->update();
                }
            } catch (\Exception $exception) {
                if ($exception instanceof ApiException) {
                    throw new ApiException(null, $exception->errors);
                }
                throw new ApiException(null, ['error' => $exception->getMessage()]);
            }
        }
        //endregion

        //region 8. Sending email for verification. Sign-up Case.
        if ($sendVerificationEmail) {
            $this->sendVerificationEmailToMember($newMember);
        }
        //endregion

        //region 9. Sending newly created member to the Pos
        $orgRepo = new OrganizationRepository();
        if( $orgRepo->verifyUntillSetting($organization)
            && $newMember->type !== Member::TYPE['CONTACT']){
            $this->pushmemberToPos($newMember->organization, $newMember);
        }
        //endregion

        //region 10. If already a member then assign its validate id to new member.
        $alreadyMember = Member::whereEmail($newMember->email)->whereNotNull('email')->first();
        if (!empty($alreadyMember) && $alreadyMember->id !== $newMember->id) {
            $validateNumber = $alreadyMember->validate_id;
            $newMember->validate_id = $validateNumber;
            $newMember->save();

            // as the  validate id is updated so push member validate card at member creation.
            if($validateNumber){
                $this->pushCardToPos($newMember,$newMember->organization,Member::CARD_NAME['MEMBERME_ID'],$validateNumber);
                $this->updateMemberCardId($organization,$newMember);
            }

        }
        //endregion

        //region 11. Assign first background Card Template and Generate its member card if not a contact.
        /** @var OrganizationCardTemplate $defaultTemplate */
        $defaultTemplate = $organization->templates()->first();
        if ($defaultTemplate && !$isContact) {
            $newMember->member_id_card = $this->generateMemberCard($defaultTemplate, $newMember);
            $newMember->organization_card_template_id = $defaultTemplate->id;
            $newMember->save();
        }
        //endregion
        $newMember->refresh();
        return $newMember;
    }

    /**
     * This function will return the authorised fields for being updated in the change field request.
     *
     * @return array
     */
    public static function AuthorizedFields()
    {
        return [
            'first_name', 'middle_name', 'last_name', 'contact_no', 'date_of_birth',
            'title', 'facebook_id', 'known_as', 'gender', 'phone', 'email', 'status', 'next_of_kin_contact_no', 'next_of_kin', 'payment_method', 'payment_frequency', 'renewal','ethnicity'
        ];
    }

    /**
     * @param Member $member
     * @param $field
     * @param $value
     * @return Member
     * @throws ApiException
     */
    public function updateField(Member $member, $field, $value)
    {

        $due = null;

        if ($field == 'status') {  //if field is status then change status and set the renewal  if resigned.
            switch ($value) {
                case IStatus::EXPIRED:
                case IStatus::INACTIVE:
                case IStatus::PENDING_NEW:
                    $due = IStatus::ACTIVE;
                    break;
                case IStatus::ACTIVE:
                    $due = IStatus::INACTIVE;
                    break;
                case IStatus::RESIGNED:
                    $due = IStatus::INACTIVE;
                    if ($member->renewal > Carbon::now()) {
                        $member->renewal = Carbon::now();
                    }
                    break;
            }
            $member->due = $due;
        }

        if ($field == 'contact_no' && empty($member->contact_no)) {
            //if field is contact_no and contact no is already empty change its status.
            $this->changeStatus($member, $member->status);
        }

        if ($field == 'status') {     //if field is status then change status and set details update date time.
            $this->changeStatus($member, $value);
            $member->details_updated_date_time = Carbon::now();
            $member->update();

        } else if ($field == 'renewal') {
            // if field is renewal change the renewal and update the id card
            $renewalCorbanDate = new Carbon($value);
            $member->$field = $renewalCorbanDate->endOfDay();
            $member->details_updated_date_time = Carbon::now();
            if ($member->template) {
                $member->member_id_card = $this->generateMemberCard($member->template, $member);
            }

            if ($renewalCorbanDate < Carbon::now()) {
                $member->financial = IStatus::INACTIVE;
            }else{
                $member->financial = IStatus::ACTIVE;
            }
            $member->update();
        } else {
            $member->$field = $value;   // updating all fields accept in the conditions before.
            $member->details_updated_date_time = Carbon::now();
            $member->member_id_card = $this->updateIdCardOnFieldChange($member, $field); // updating id card on field change.
            $member->save();
        }

        if ($field == 'email') {
            $memberProfile = $member->profile;
            $member->api_token = Str::random(60);
            $member->save();
            if (empty($memberProfile)) {

                $memberProfile = new MemberProfile();
                $memberProfile->fill($member->toArray());
                $memberProfile->save();
                /*try{
                    Mail::to($value)->send(new AddMemberEmail($member->organization,$member));
                    $member->password_sent_date_time = Carbon::now();
                    $member->password = md5(Str::random(8));
                    $member->api_token = Str::random(60);
                    $member->update();
                }catch (\Exception $exception){
                    return false;
                }*/
            }
        }
        /** @var Organization $organization */
        $organization = $member->organization;
        $orgRepo = new OrganizationRepository();
        try {
            if (
                in_array($field, Member::POS_FIELDS)
                && $orgRepo->verifyUntillSetting($organization)
                && $member->type !== Member::TYPE['CONTACT']
            ) {
                $this->pushmemberToPos($member->organization, $member);
            }
        } catch (\Exception $exception) {
            \Log::info('Pos Issue: ' . $exception->getMessage());
        }
        return $member;
    }

    public function addMemberChangeLog(Member $member, $fieldName, $oldValue, $newValue){
        ChangeLog::create(
            [
                'field_name' => $fieldName,
                'old_value' => $oldValue,
                'new_value' => $newValue,
                'entity_id' => $member->id,
                'model' => Member::class,
                'status' => IStatus::ACTIVE,
                'type' => ChangeLog::TYPE['MEMBER']

            ]
        );
    }
    /**
     * @param MemberOther $memberOther
     * @param $field
     * @param $value
     * @return MemberOther
     * @throws ApiException
     */
    public function updateOtherField(MemberOther $memberOther, $field, $value)
    {
        /* @var $member Member */
        $member = $memberOther->member;

        //region Handling receive_sms
        if ($field == 'receive_sms') {
            $groups = $member->groups()->whereHas('smsList')->get();

            if ($value == IStatus::ACTIVE) {
                foreach ($groups as $group) {
                    /* @var $group Group */
                    /* @var $smsListMember SmsListMember */
                    $smsListMember = $group->smsListMember()->where('member_id', $member->id)->first();
                    if (empty($smsListMember)) {

                        /* @var $smsList SmsList */
                        $smsList = $group->smsList;
//                        $this->addToSmslist($group->organization, $smsList, $member, $group);
                    }
                }
            } else if ($value == IStatus::INACTIVE) {
                foreach ($groups as $group) {
                    /* @var $group Group */
                    /* @var $smsListMember SmsListMember */
                    $smsListMember = $group->smsListMember()->where('member_id', $member->id)->first();
                    if (!empty($smsListMember)) {

                        /* @var $smsList SmsList */
                        $smsList = $group->smsList;
                        $this->deleteMemberFromSmsList($group->organization, $smsList, $member);
                    }
                }
            }
        }
        //endregion
        if($field == 'prox_card' && !empty($value)){
            if(!$this->checkOthersUnique($field,$value, $member->organization_id)){
                throw new ApiException(null, ['error' => 'Prox Card Already Exists']);
            }
        }else if ( $field == 'swipe_card' && !empty($value)){
            if(!$this->checkOthersUnique($field,$value, $member->organization_id)){
                throw new ApiException(null, ['error' => 'Swipe Card Already Exists']);
            }
        }
        $memberOther->$field = $value;
        $memberOther->update();
        $this->addDetailUpdatedDateTime($member);

        /** @var Organization $organization */
        $organization = $member->organization;

        /** @var OrganizationRepository $orgRepo */
        $orgRepo = new OrganizationRepository();
        if (
            in_array($field, Member::POS_FIELDS)
            && $orgRepo->verifyUntillSetting($organization)
            && $member->type !== Member::TYPE['CONTACT']
        ) {
            if($field == 'prox_card'){
                try{
                    //region Pushing member to pos if not created.
                    if(!$member->untill_id){
                        $this->pushmemberToPos($organization, $member);
                    }
                    //endregion
                    $this->pushCardToPos($member,$organization,Member::CARD_NAME['PROX_CARD'],$value);

                    //we'll get all member cards and map the prox_card into the database.
                    $this->updateMemberCardId($organization,$member);
                }catch (\Exception $exception){
                    \Log::info('Unable to push Prox Card: '.$member->member_id.' '. $exception->getMessage());
                }

            }else if($field == 'swipe_card') {
                try {
                    //region Pushing member to pos if not created.
                    if (!$member->untill_id) {
                        $this->pushmemberToPos($organization, $member);
                    }
                    //endregion
                    $this->pushCardToPos($member, $organization, Member::CARD_NAME['SWIPE_CARD'], $value);

                    //we'll get all member cards and map the swipe_card into the database.
                    $this->updateMemberCardId($organization,$member);
                }catch (\Exception $exception) {
                    \Log::info('Unable to push Swipe Card'.$member->member_id.' '. $exception->getMessage());

                }
            }else{
                $this->pushmemberToPos($member->organization, $member);
            }
        }
        return $memberOther;
    }

    public function updateMemberCardId(Organization $organization, Member $member)
    {
        try{
            if($member->untill_id){
                $untillService = new UntillService($organization);
                $clientCards = $untillService->getClientCards($member->untill_id);
                $this->savePosClientCards($member,$clientCards);

            }
        }catch (\Exception $exception){

        }
    }

    public function pushCardToPos(Member $member, Organization $organization,$cardType,  $cardValue)
    {
        $proxCardName = $this->getCardName($organization, $cardType);
        $untillCardId = null;
        
        if($proxCardName){
            $proxCardNameId = $proxCardName->data['value'];
            $untillCardId = $this->checkIfClientCardExists($organization, $member,$cardType);
            $untilService = new UntillService($organization);
            $untilService->updateCardInfo($member->untill_id,$proxCardNameId,$cardValue, $untillCardId);
        }else{
            \Log::info('Card Name not found against the '. $organization->id. ' , Card Type: '. $cardType);
        }
    }


    /**
     * Will return the pos card Id if the card Exists.
     * @param Organization $organization
     * @param Member $member
     * @param $cardType
     * @return |null |null |null
     */
    public function checkIfClientCardExists(Organization $organization, Member $member,$cardType)
    {
        $posCardName = $this->getCardName($organization, $cardType);
        $untillCardId = null;
        if($posCardName){
            if($member->untill_data){
                $untillCardNameRecord = $this->getCardName($organization , $cardType);
                if(!empty($untillCardNameRecord)){
                    $untillCardId = (!empty($member->untill_data['client_cards']))
                        ? ( isset($member->untill_data['client_cards'][array_get($untillCardNameRecord->data,'value')]) )
                            ? $member->untill_data['client_cards'][array_get($untillCardNameRecord->data,'value')]: null
                        : null;
                }
            }

        }
        return $untillCardId;
    }

    public function getCardName(Organization $organization, $posCardName)
    {
        return Record::where([
            'record_type_id' => IRecordType::POS_CLIENT_CARD_NAME,
            'name' => $posCardName,
            'organization_id' => $organization->id
        ])->first();
    }

    /**
     * @param $data array
     * @param $type integer physical or postal
     * @return \Illuminate\Database\Eloquent\Model|null|static
     * @throws ApiException
     */
    public function addAddress($data, $type)
    {
        $member = Member::whereId(array_get($data, 'member_id'))->with('physicalAddress', 'postalAddress')->first();

        //region Saving Address
        //region Creating Or Getting Object To Update
        if ($type == AddressType::PHYSICAL_ADDRESS) {
            $address = $member->physicalAddress;
        } else if ($type == AddressType::POSTAL_ADDRESS) {
            $address = $member->postalAddress;
        }
        if (empty($address)) {
            $address = new Address();
        }
        //endregion


        $oldAddress = $address->address1. ' '. $address->address2 . ' ' . $address->city. ' ' . $address->region. ' ' . $address->postal_code. ' ' . $address->latitude. ' ' . $address->longitude;
        //region Adding Details to Address
        $address->country_id = array_get($data, 'country');
        if (empty($address->country_id)) {
            $address->country_id = array_get($data, 'country_id');
        }

        $address->address1 = array_get($data, 'first_address');
        if (empty($address->address1)) {
            $address->address1 = array_get($data, 'address1');
        }

        $address->address2 = array_get($data, 'second_address');

        $address->suburb = array_get($data, 'suburb');
        $address->city = array_get($data, 'city');
        $address->region = array_get($data, 'region');
        $address->latitude = array_get($data, 'latitude');
        $address->longitude = array_get($data, 'longitude');
        $address->status_id = IStatus::ACTIVE;
        $address->postal_code = array_get($data, 'postal_code');;
        $address->item_id = array_get($data, 'member_id');
        $address->address_type_id = $type;
        $address->item_type_id = AddressType::MEMBER;
        if (empty($address->address2)) {

            $address1 = str_replace($address->city, '', $address->address1);
            $address1 = str_replace(', '.$address->suburb.',', '', $address1);
            $address1 = str_replace($address->postal_code, '', $address1);
            $address->address2 =  $address1;
        }


        $address->save();
        $newAddress = $address->address1. ' '. $address->address2 . ' ' . $address->city. ' ' . $address->region. ' ' . $address->postal_code. ' ' . $address->latitude. ' ' . $address->longitude;
        //endregion
        //endregion

        //region Attatching address to member
        if ($member) {
            if ($type == AddressType::PHYSICAL_ADDRESS) {
                $member->physical_address_id = $address->id;
                $this->addMemberChangeLog($member,ChangeLog::FIELD['PHYSICAL_ADDRESS'], $oldAddress,$newAddress);
            } else if ($type == AddressType::POSTAL_ADDRESS) {
                $member->postal_address_id = $address->id;
                $this->addMemberChangeLog($member,ChangeLog::FIELD['POSTAL_ADDRESS'], $oldAddress,$newAddress);

            }
            $member = $this->addDetailUpdatedDateTime($member,false);
            $member->update();
            $member->refresh();
        }

        $organization = $member->organization;
        $organizationRepo = new OrganizationRepository();
        if (
            $type == AddressType::PHYSICAL_ADDRESS
            && (!empty($data['address1']) || !empty($data['first_address']))
            && $organizationRepo->verifyUntillSetting($organization)
            && $member->type !== Member::TYPE['CONTACT']
        ) {
            $this->pushMemberToPos($member->organization, $member);
        }
        //endregion

        return $member;
    }

    /**
     * @param $organization_id
     * @return int|mixed|null
     */
    public function generateNextMemberNumber($organization_id)
    {
        $organiation = Organization::find($organization_id);
        $lastMember = Member::where(['organization_id' => $organization_id])->latest()->first();
        $nextMemberNumberFound = false;
        if ($organiation) {
            $organizationDetail = $organiation->details;
            $nextMemberId = null;
            if ($organizationDetail) {

                //  if organization details is set, then find a person with last next member number.
                if (!empty($organizationDetail->next_member)) {
                    $memberHavingNextNumber = $organiation->members()->where('member_id', $organizationDetail->next_member)->first();

                    if ($memberHavingNextNumber) {
                        //if next member number already applied to the member, then the member number will be the last member number + 1
                        if (!empty($lastMember)) {
                            $nextMemberId = $lastMember->member_id;
                            $nextMemberNumberFound = true;
                        }

                    } else {
                        $nextMemberId = $organiation->details->next_member;
                        $nextMemberNumberFound = true;
                    }

                    if ($nextMemberId == $organiation->details->next_member) {
                        $memberCount = $organiation->members()->count();
                        if ($memberCount == 0 || empty($memberHavingNextNumber)) {
                            $nextMemberId = $nextMemberId;
                        } else {
                            $nextMemberId = $nextMemberId + 1;
                        }

                    } else {
                        $nextMemberId = $nextMemberId + 1;
                    }
                } else {
                    if ($lastMember) {
                        $lastMemberId = $lastMember->member_id;
                        $nextMemberId = $lastMemberId + 1;
                    } else {
                        $nextMemberId = 1;
                    }
                }
            } else {

                //if organization next member number not set, so add a member with 1, and next time that will be incremented 1
                if ($lastMember) {
                    $lastMemberId = $lastMember->member_id;
                    $nextMemberId = $lastMemberId + 1;
                } else {
                    $nextMemberId = 1;
                }
            }
            return $nextMemberId;
        }
        return 0;
    }

    public function generateNextMemberNo($organization_id)
    {
        $organizationDetails = OrganizationDetail::where('organization_id', $organization_id)->first();
        if ($organizationDetails) {
            return $organizationDetails->next_member;
        }
        return 1;
    }

    /**
     * Will save the next member number to OrganizationDetails by telling it from DB.
     * @param $organization_id
     */
    public function saveNextMember($organization_id)
    {
        $missing = 1;
        $organizationDetails = OrganizationDetail::where('organization_id', $organization_id)->first();

        //add counter to the organization next member
        if (isset($organizationDetails->next_member) && !empty($organizationDetails->next_member)) {
            $missing = $organizationDetails->next_member + 1;
        }

        $lastMemberNumber = Member::where('organization_id', $organization_id)->max('member_id');

        if ($missing <= $lastMemberNumber && $organizationDetails) {

//            $missing = $this->findFirstMissingNumber($organization_id);       //todo understand the reason of this statement, if no need, keep it as a comment.

            $missing = $organizationDetails->next_member;
            $missing = $this->findUnmatchedNumber($missing, $organization_id);
        }

        //saving the data  in the database org next.
        if ($organizationDetails) {
            $organizationDetails->next_member = $missing;
            $organizationDetails->save();
        }
    }

    /**
     * This function will return either the next Id by members table or return the missing Id's in range of 1 to max
     * @param $organization_id
     * @return int|mixed
     */
    public function findFirstMissingNumber($organization_id)
    {

        $organization = Organization::find($organization_id);   //todo pass the organization in code re-factoring phase.

        $allMember = $organization->members()->orderBy('member_id', 'asc')->pluck('member_id')->toArray();

        $fullRange = [];
        if (!empty($allMember)) {
            $fullRange = range(1, max($allMember));
        }

        $missingArray = array_diff($fullRange, $allMember);
        $missing = array_first($missingArray);
        if (!$missing) {
            $lastMember = $organization->members()->latest()->first();
            if ($lastMember) {
                $lastMemberId = $lastMember->member_id;
                $missing = ++$lastMemberId;
            } else {
                $missing = 1;
            }
        }
        return $missing;
    }

    /**
     * Finds the next unmatched Number one by one. w.r.t member no provided.
     */
    public function findUnmatchedNumber($member_id, $organization_id)
    {
        $member = Member::where(['organization_id' => $organization_id, 'member_id' => $member_id])->first();
        while (!empty($member)) {
            $memberIdToCheck = $member->member_id;
            $memberIdToCheck++;
            return $this->findUnmatchedNumber($memberIdToCheck, $organization_id);
        }
        return $member_id;
    }


    public function addSubscription(Member $member, Subscription $subscription)
    {
        if ($member->subscription_id != $subscription->id) {
            $member->status = IStatus::PENDING_NEW;
        }

        $member->subscription_id = $subscription->id;
        $member->subscription = $subscription->title;

        $subscriptionStartDateSettings = !empty($subscription->organization->organizationSettings->subscription_start_date) ? $subscription->organization->organizationSettings->subscription_start_date : null;

        if (!empty($subscriptionStartDateSettings) && $subscriptionStartDateSettings == OrganizationSetting::SUBSCRIPTION_DROPDOWN_OPTION['DATE_SUBSCRIPTION_ASSIGNED']) {
            $member->subscription_start_date = Carbon::now();
        }

        $member->subscription_assign_date = Carbon::now();

//        $member->due = IStatus::INACTIVE;
//        $member->financial = IStatus::INACTIVE;

        $this->changeStatus($member, IStatus::PENDING_NEW);
        if ($member->template) {
            $member->member_id_card = $this->generateMemberCard($member->template, $member);
        }
        $member = $this->addDetailUpdatedDateTime($member, false);
        $member->save();
        $member->refresh();
        return $member;
    }

    /**
     * @param Member $member
     * @throws ApiException
     * @throws \SendGrid\Mail\TypeException
     */
    public function sendResetPasswordEmail(Member $member)
    {
        if (empty($member->email)) {
            throw  new ApiException(null, ['email' => 'Member has not email']);
        }

        $emailTemplate = EmailTemplate::whereOrganizationId(15550)->memberResetPassword()->first();
        if (!empty($emailTemplate)) {
            $emailRepository = new EmailTemplateRepository();

            /** @var Subscription $subscription */
            $subscription = $member->subscription()->first();
            $sendgridService = new \App\Services\Sendgrid\SendgridService();
            $sendgridService->setup($member->organization, SendgridSetting::whereOrganizationId(15550)->first());

            $dynamicParameters = [
                'first_name' => $member->first_name,
                'last_name' => $member->last_name,
                'full_name' => $member->full_name,
                'member_no' => $member->member_id,
                'subscription' => (!empty($subscription)) ? $subscription->title : '',
                'member_reset_password_link' => route('verifyMemberPasswordReset', $member->verify_token . '-_-' . base64_encode($member->email))
            ];

            $email = $sendgridService->setupMail(array_get($member, 'email'), $dynamicParameters);
            $email = $sendgridService->setTemplateId(
                $email
                , $emailTemplate->template_id
            );
            $sendgridService->send($email);


        } else {
            Mail::to(array_get($member, 'email'))->send(new SendResetEmailForMembers($member));
        }
    }

    /**
     * @param MemberProfile $profile
     * @param $data
     * @return MemberProfile
     */
    public function updatePersonalProfile(MemberProfile $profile, $data)
    {
        $profile->first_name = array_get($data, 'first_name', $profile->first_name);
        $profile->last_name = array_get($data, 'last_name', $profile->last_name);
        $profile->gender = array_get($data, 'gender', $profile->gender);
        $profile->contact_no = array_get($data, 'contact_no', $profile->contact_no);
        $profile->country_code = array_get($data, 'country_code', $profile->country_code);
        $profile->middle_name = array_get($data, 'middle_name', $profile->middle_name);
        $profile->title = array_get($data, 'title', $profile->title);
        $profile->facebook_id = array_get($data, 'facebook_id', $profile->facebook_id);
        $profile->known_as = array_get($data, 'known_as', $profile->known_as);
        $profile->phone = array_get($data, 'phone', $profile->phone);

        if (empty($profile->country_code)) {
            try {
                $profile = $this->resolveCountryCodeAndMobileNumber($profile, array_get($data, 'contact_no', $profile->contact_no));
            } catch (\Exception $e) {
                \Log::info('There is issue with resolving contact no in Updateing Personal profile: ' . array_get($data, 'contact_no'), $e->getMessage());
            }
        }

        //region Change email case
        if (!empty(array_get($data, 'email')) && $profile->email != array_get($data, 'email')) {
            $profile->email_to_change = array_get($data, 'email');
            $profile->verify_token = Str::random(60);
            $profile->verify_link_sent_date_time = Carbon::now();
            $profile->update();

            try {
                Mail::to(array_get($data, 'email'))->send(new SendEmailChangeEamil($profile->member, $profile->verify_token));
            } catch (\Exception $exception) {
                \Log::info($exception->getMessage() . __FUNCTION__);
            }
        }
        //endregion

        //region Date of birth case.
        if (!empty($data['date_of_birth'])) {
            try {
                $date = date('Y-m-d', DateTime::createFromFormat('d/m/Y', $data['date_of_birth'])->getTimestamp());
                $profile->date_of_birth = $date;
            } catch (Exception $exception) {

                \Log::info($exception->getMessage());
            }
        }
        //endregion

        if (!empty($data['country']) || !empty($data['address']) || !empty($data['postal_code']) || !empty($data['city'])) {
            $physicalAddress = $profile->physicalAddress;
            if (empty($physicalAddress)) {
                $physicalAddress = new Address();
                $physicalAddress->address_type_id = AddressType::PHYSICAL_ADDRESS;
            }
            $physicalAddress->item_id = $profile->id;
            $physicalAddress->item_type_id = AddressType::MEMBER_PROFILE;
            $physicalAddress->country_id = array_get($data, 'country');
            $physicalAddress->city = array_get($data, 'city');
            $physicalAddress->postal_code = array_get($data, 'postal_code');
            $physicalAddress->address1 = array_get($data, 'address');
            $physicalAddress->save();
            $profile->physical_address_id = $physicalAddress->id;
        }
        $profile->update();

        $profile = MemberProfile::whereId(array_get($profile, 'id'))->with([
            'member' => function ($query) {
                $query->select('id', 'first_name', 'member_id', 'last_name', 'middle_name');
            },
            'physicalAddress',
        ])->first();

        $member = $profile->member;
        $member->setAppends([]);
        return $profile;
    }

    /**
     * @param Organization $organization
     * @param SmsList $list
     * @param Member $member
     *
     * @param Group $group
     * @return SmsListMember|\Illuminate\Database\Eloquent\Model|null|static
     * @throws ApiException
     */
    public function addToSmslist(Organization $organization, SmsList $list, Member $member, Group $group)
    {
        $smsListMember = $list->smsListMember()->where('member_id', $member->id)->first();
        if (empty($smsListMember) && !empty($member->contact_no)) {
            $this->smsService->setup($organization);
            try {
                $contactAddedToList = $this->smsService->addToList($list->ref_id, prepare_number($member->contact_no), $member->first_name, $member->last_name, []);
            } catch (MemberAlreadyInList $exception) {

            }

            /* @var $smsListMember SmsListMember */
            $smsListMember = new SmsListMember();
            $smsListMember->group_id = $group->id;
            $smsListMember->member_id = $member->id;
            $smsListMember->sms_list_id = $list->id;
            $smsListMember->save();
        }
        return $smsListMember;
    }

    /**
     * @param Organization $organization
     * @param SmsList $list
     * @param Member $member
     * @throws ApiException
     */
    public function deleteMemberFromSmsList(Organization $organization, SmsList $list, Member $member)
    {
        $this->smsService->setup($organization);
        try {
            $this->smsService->removeFromList($list->ref_id, prepare_number($member->contact_no));
        } catch (MemberNotExistInListException $exception) {

        }
        $list->smsListMember()->where('member_id', $member->id)->delete();
    }

    /**
     * This is recursive function that will return all the parents of a member passed to it.
     *
     * @param Member $member
     * @return array
     */
    public function getParents(Member $member)
    {
        $allParent = [];
        if ($member->parent_code) {
            $parent = Member::where('organization_id', $member->organization_id)
                ->whereNotNull('subscription_id')
                ->where('member_id', $member->parent_code)->first();
            if ($parent) {
                $allParent[] = $parent;
                $newParent = $this->getParents($parent);
                if (!empty($newParent)) {
                    $allParent[] = $newParent;
                }
            }
        }
        return $allParent;
    }

    /**
     * This is a recursive function  which will return all the child members of a member.
     *
     * @param Member $member
     * @return array
     */
    public function getChildren(Member $member)
    {
        $allChildren = [];
        $children = Member::where('organization_id', $member->organization_id)
            ->whereNotNull('subscription_id')
            ->where('parent_code', $member->member_id)->get();

        if (!$children->isEmpty()) {
            $allChildren[] = $children;
            foreach ($children as $child) {
                $result = $this->getChildren($child);
                if (!empty($result)) {
                    $allChildren[] = $result;
                }
            }
        }
        return $allChildren;
    }

    /**
     * Sending verification Email to member.
     *
     * @param Member $member
     */
    public function sendVerificationEmailToMember(Member $member)
    {
        try {
            if (!empty($member->email)) {
                Mail::to(array_get($member, 'email'))->send(new SendMemberVerificationEmail($member));
            }
        } catch (\Exception $exception) {
            \Log::info($exception->getMessage());
        }

    }

    /**
     * @param array $data
     * @return MemberEmployment
     */
    public function createEmployment($data = [])
    {
        /* @var $memberEmployment MemberEmployment */
        if (!empty($data['id'])) {
            $memberEmployment = MemberEmployment::find($data['id']);
        }

        if (empty($memberEmployment)) {
            $memberEmployment = new MemberEmployment();
        }

        $memberEmployment->member_id = array_get($data, 'member_id', $memberEmployment->member_id);
        $memberEmployment->date_to = array_get($data, 'date_to', $memberEmployment->date_to);
        $memberEmployment->date_from = array_get($data, 'date_from', $memberEmployment->date_from);
        $memberEmployment->role = array_get($data, 'role', $memberEmployment->role);
        $memberEmployment->employer = array_get($data, 'employer', $memberEmployment->employer);
        $memberEmployment->save();

        return $memberEmployment;
    }

    /**
     * @param array $data
     * @return MemberEducation
     */
    public function createEducation($data = [])
    {
        /* @var $memberEducation MemberEducation */
        if (!empty($data['id'])) {
            $memberEducation = MemberEducation::find($data['id']);
        }

        if (empty($memberEducation)) {
            $memberEducation = new MemberEducation();
        }

        $memberEducation->member_id = array_get($data, 'member_id', $memberEducation->member_id);
        $memberEducation->date_to = array_get($data, 'date_to', $memberEducation->date_to);
        $memberEducation->date_from = array_get($data, 'date_from', $memberEducation->date_from);
        $memberEducation->institution = array_get($data, 'institution', $memberEducation->institution);
        $memberEducation->qualification = array_get($data, 'qualification', $memberEducation->qualification);
        $memberEducation->save();

        return $memberEducation;
    }

    public function getMemberMessages(Member $member)
    {
        $smsListMembers = [];
        if (!empty($member->smsListMember)) {
            $smsListMembers = \DB::table('sms_list_members')
                ->join('sms_lists', 'sms_lists.id', '=', 'sms_list_members.sms_list_id')
                ->join('list_sent_sms', 'list_sent_sms.sms_list_id', '=', 'sms_lists.id')
                ->where('sms_list_members.member_id', $member->id)
                ->orderBy('list_sent_sms.id', 'desc')
                ->select('list_sent_sms.message', 'list_sent_sms.created_at as sent_date_time')
                ->groupBy('list_sent_sms.created_at')
                ->get()
                ->toArray();
        }
        return $smsListMembers;
    }

    public function generateValidateId()
    {
        do {
            $randomStr = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 3);
            $randomInt = rand(100, 999);
            $validateId = $number = $randomStr . '' . $randomInt;
            $member = Member::where('validate_id', $validateId)->first();
        } while (!empty($member));

        return $validateId;
    }

    /**
     * Change member status. remove from old status group and sms list and add that member into the new status
     * sms list
     * @param Member $member
     * @param int $status
     * @throws ApiException
     */
    public function changeStatus(Member $member, $status = IStatus::PENDING_NEW)
    {
        //get old status Group.
        $group = $member->groups()->where([
            'type' => Group::TYPE['STATUS'],
            'is_status_group' => IStatus::ACTIVE,
        ])->first();

        $organization = $member->organization;

        if (!empty($group)) {
            //todo : Need perfection, implement a check here if memebr is successfully deleted from brustsms then delete from system.
//            if (!empty($group->smsList)) {
//                if (!empty($member->contact_no)) {
//                    try {
////                        $this->deleteMemberFromSmsList($organization, $group->smsList, $member);    // member deleted from the brustsms
//                    } catch (\Exception $e) {
//                        \Log::info('Unable to delete member from BrustSms list' . $e->getMessage());
//                    }
//                }
//            }
            DB::table('group_member')           // group detatched
            ->join('groups', 'group_id', '=', 'groups.id')
                ->where('type', Group::TYPE['STATUS'])
                ->where('is_status_group', IStatus::ACTIVE)
                ->where('group_member.member_id', $member->id)
                ->where('group_member.group_id', $group->id)
                ->delete();
        }

        //region Finding Status To get Group
        $statuses = DropdownHelper::memberStatusList();
        try {
            $statusToFind = $statuses[$status];
        } catch (\Exception $exception) {
            throw new ApiException(null, ['error' => 'Invalid Member Status']);
        }
        //endregion

        //region Finding group of the status we found.
        $group = Group::where([
            'organization_id' => $member->organization_id,
            'is_status_group' => IStatus::ACTIVE,
            'name' => $statusToFind,
        ])->first();
        //endregion

        //region If group is not already present, creating the new group.
        if (empty($group)) {
            $group = new Group();
            $group->name = $statusToFind;
            $group->is_status_group = IStatus::ACTIVE;
            $group->organization_id = $member->organization_id;
            $group->type = Group::TYPE['STATUS'];
            $group->save();
        }
        //endregion

        $smsList = $group->smsList;

        //region Adding Sms List if the Sms lis isn't already present for that group and Brustsms is setup.
        if (empty($smsList) && !empty($organization->smsSetting)) {
            $orgRepo = new OrganizationRepository();
            try {
//                $smsList = $orgRepo->addSmsList($group->name, $group);
            } catch (\Exception $exception) {
//                \Log::info('Unable to add sms list in status change.' . $exception->getMessage());
            }
        }
        //endregion


        //region If member have contact no. add that to the sms list we've created.
        if (!empty($member->contact_no) && !empty($smsList)) {
            try {
//                $smsListMember = $this->addToSmslist($organization, $smsList, $member, $group);
            } catch (\Exception $e) {
//                \Log::info('Unable to add member' . $member->id . ' to sms List.' . $smsList->id . $e->getMessage());
            }
        }
        //endregion

        $member->groups()->save($group);

        $member = $this->addDetailUpdatedDateTime($member,false);
        $member->status = $status;
        $member->save();
    }


    /**
     * Return member profile object by just setting contact_no and country code.
     * @param MemberProfile $memberProfile
     * @param $contactNo
     * @return MemberProfile
     */
    public function resolveCountryCodeAndMobileNumber(MemberProfile $memberProfile, $contactNo)
    {
        if (!empty($contactNo)) {
            $explodedContactNo = explode('-', $contactNo);
            $countryCode = array_get($explodedContactNo, 0, null);
            $contactNoWithoutCountryCode = array_get($explodedContactNo, 1, null);
            if (count($explodedContactNo) < 2) {
                $memberProfile->contact_no = $countryCode;
            } else {
                $memberProfile->country_code = $countryCode;
                $memberProfile->contact_no = $contactNoWithoutCountryCode;
            }
        }

        return $memberProfile;

    }

    /**
     * @param MemberDirectPaymentDetails $memberDirectPaymentDetails
     * @param $field
     * @param $value
     * @return MemberDirectPaymentDetails
     */
    public function updateMemberDirectPaymentDetails(MemberDirectPaymentDetails $memberDirectPaymentDetails, $field, $value)
    {
        //region if field is memebr next payment date than parsing date into the right format.
        if ($field == 'next_payment_date') {
            $explodedDate = explode("/", $value);
            if (count($explodedDate) == 3 && checkdate($explodedDate[1], $explodedDate[0], $explodedDate[2])) {
                $dashDate = str_replace('/', '-', $value);
                $value = date('Y-m-d h:s:i', strtotime($dashDate));
            }
        }
        //endregion

        //adding logs.
        (new MemberRepository())->addMemberChangeLog($memberDirectPaymentDetails->member, 'direct_payment-'.$field,$memberDirectPaymentDetails->$field,$value);
        $memberDirectPaymentDetails->$field = $value;
        $memberDirectPaymentDetails->save();
        return $memberDirectPaymentDetails;
    }

    /**
     * Add member to draw as important.
     *
     * @param Member $member
     * @param Draw $draw
     * @return null|Draw $draw
     */
    public function addMemberToDraw(Member $member, Draw $draw)
    {
        if ($this->isMemberValidForDrawEntry($member, $draw)) {
            $drawEntry = new DrawEntry();
            $drawEntry->member_id = $member->id;
            $drawEntry->draw_id = $draw->id;
            $drawEntry->organization_id = $draw->organization_id;
            $drawEntry->entry_date_time = Carbon::now();
            $drawEntry->save();
            return $draw;
        }
        return null;
    }

    public function isMemberValidForDrawEntry(Member $member, Draw $draw)
    {
        if ($draw->frequency_limit == Draw::FREQUENCY_LIMIT['YES']) {
            $frequencyLimitQuantity = $draw->frequency_limit_quantity;
            $frequencyLimitPeriod = $draw->frequency_limit_quantity_period;
            $lastMemberEntryForThisDraw = $draw->entries()->where('member_id', $member->id)->orderBy('id', 'desc')->first();

            if (empty($lastMemberEntryForThisDraw)) {
                return true;
            }

            if($frequencyLimitPeriod == Draw::FREQUENCY_LIMIT_PERIOD['HOURS']){
                $memberLastEntryTime = new Carbon(array_get($lastMemberEntryForThisDraw, 'entry_date_time'));
                $currentDate = Carbon::now();
                $diff = $currentDate->diff($memberLastEntryTime);
            }else{
                $memberLastEntryTime = new Carbon(array_get($lastMemberEntryForThisDraw, 'entry_date_time'));
                $memberLastEntryTime = $memberLastEntryTime->startOfDay();
                $currentDate = Carbon::now()->startOfDay();
                $diff = $currentDate->diff($memberLastEntryTime);
            }

            switch ($frequencyLimitPeriod) {
                case Draw::FREQUENCY_LIMIT_PERIOD['DRAW']:
                    //there is one entry in draw as in draw mode one entry can be possible in a draw. ( refer to docs. )
                    return false;
                    break;

                case Draw::FREQUENCY_LIMIT_PERIOD['DAYS']:
                    if ($diff->days < $frequencyLimitQuantity) {
                        return false;
                    } else {
                        return true;
                    }
                    break;

                case Draw::FREQUENCY_LIMIT_PERIOD['HOURS']:
                    if ($diff->h < $frequencyLimitQuantity) {
                        return false;
                    } else {
                        return true;
                    }
                    break;

                case Draw::FREQUENCY_LIMIT_PERIOD['MONTHS']:
                    if ($diff->m < $frequencyLimitQuantity) {
                        return false;
                    } else {
                        return true;
                    }
                    break;
                case Draw::FREQUENCY_LIMIT_PERIOD['WEEKS']:
                    $days = $diff->days;
                    $week = floor($days / 7);
                    if ($week < $frequencyLimitQuantity) {
                        return false;
                    } else {
                        return true;
                    }
                    break;

                default:
                    return false;
            }

        } else if ($draw->frequency_limit == Draw::FREQUENCY_LIMIT['NO']) {
            return true;
        }

        return false;
    }

    /**
     * @param $email
     * @param Organization $organization
     * @param Member $member
     * @param null $password
     * @throws ApiException
     * @throws \SendGrid\Mail\TypeException
     */
    public function sendNewMemberEmail($email, Organization $organization, Member $member, $password = null)
    {
        /** @var EmailTemplate $emailTemplate */
        $emailTemplate = $organization->emailTemplates()->newMember()->first();

        if (empty($emailTemplate->template_id)) {
            $emailTemplate = EmailTemplate::whereOrganizationId(15550)->newMember()->first();
        }


        if (empty ($emailTemplate)) {
            \Log::info($organization->name . ' ' . 'Email template not found' . EmailTemplate::TYPE['NEW_MEMBER']);
            return;
        }
        if (!empty($email)) {

            /** @var Subscription $subscription */
            $subscription = $member->subscription()->first();
            $sendgridService = new \App\Services\Sendgrid\SendgridService();
            $sendgridService->setup($organization);

            $dynamicParameters = [
                'first_name' => $member->first_name,
                'last_name' => $member->last_name,
                'full_name' => $member->full_name,
                'member_no' => $member->member_id,
                'subscription' => (!empty($subscription)) ? $subscription->title : ''
            ];


            $email = $sendgridService->setupMail($email, $dynamicParameters);
            $email = $sendgridService->setTemplateId(
                $email
                , $emailTemplate->template_id
            );
            $sendgridService->send($email);
        }
    }

    /**
     * @param Organization $organization
     * @param Member|array $members
     * @throws ApiException
     */
    public function pushMembersToPos(Organization $organization, $members = [])
    {
        foreach ($members as $member) {
            $this->pushMemberToPos($organization, $member);
            $this->pushCardsToPos($organization,$member);
            $this->updateMemberCardId($organization,$member);
        }
    }


    /**
     * @param Organization $organization
     * @param Member $member
     * @throws ApiException
     */
    public function pushMemberToPos(Organization $organization, Member $member)
    {
        try{
            $untilService = new UntillService($organization);
        }catch (ApiException $e){
            throw new ApiException(null,['error' => 'Please Complete Pos Setup First']);
        }
        $data = $member;
        $data['address'] = ($member->physicalAddress) ? $member->physicalAddress->address1 : null;
        $data['swipe_card'] = ($member->others) ? $member->others->swipe_card : null;
        $data['country'] = ($member->physicalAddress) ? (($member->physicalAddress->country) ? $member->physicalAddress->country->name : null) : null;
        $data['address'] = ($member->physicalAddress) ? (($member->physicalAddress->address1) ? $member->physicalAddress->address1 : null) : null;
        $untilId = $untilService->addOrUpdateClient($data);
        $member->refresh();
        $member->untill_id = (!empty($untilId))? $untilId : $member->untill_id;
        $member->save();
    }

    /**
     * Generate Member Card and get its link.
     * @param OrganizationCardTemplate $template
     * @param Member $member
     * @return string
     * @throws ApiException
     */
    public function generateMemberCard(OrganizationCardTemplate $template, Member $member)
    {

        $yAddition = 18;
        $yAdditionElements = 20;
        $xAdditionElements = 5;
        $defaultSize = 17;
        $fontData = [
            'font-size' => $defaultSize,
            'font-file' => 'times-new-roman.ttf',
            'color' => '#000000'
        ];
        $templateBackground = $template->url;
        $templateBackground = str_replace('https', 'http',$templateBackground);

        $image = \Image::make($templateBackground)->resize(432, 275, function ($constraint) {
            $constraint->upsize();
        });

        $memberRenewal = $member->renewal;
        if (!empty($memberRenewal)) {
            $memberRenewal = date('d/m/Y', strtotime($memberRenewal));
        }

        if(!$template->coordinates){
            throw new ApiException(null,null,'Unable to generate the card, Please verify the Template Design '. $template->label, IResponseCode::INTERNAL_SERVER_ERROR);
        }

        writeOnImage($image, $member->full_name, (int)$template->coordinates['_name2']['x'] + $xAdditionElements, (int)$template->coordinates['_name2']['y'] + $yAdditionElements, $fontData);
        writeOnImage($image, ($member->subscription()->first()) ? $member->subscription()->first()->title : '', (int)$template->coordinates['_subscription2']['x'] + $xAdditionElements, (int)$template->coordinates['_subscription2']['y'] + $yAdditionElements, $fontData);
        writeOnImage($image, (!empty($template->element_labels['subscription'])) ? $template->element_labels['subscription'] : 'Subscription', (int)$template->coordinates['_subscription']['x'], (int)$template->coordinates['_subscription']['y'] + $yAddition, $fontData);
        writeOnImage($image, (!empty($template->element_labels['first_name'])) ? $template->element_labels['first_name'] : 'Member Name', (int)$template->coordinates['_name']['x'], (int)$template->coordinates['_name']['y'] + $yAddition, $fontData);
        writeOnImage($image, (!empty($template->element_labels['renewal'])) ? $template->element_labels['renewal'] : 'Expiry', (int)$template->coordinates['_expiry']['x'], (int)$template->coordinates['_expiry']['y'] + $yAddition, $fontData);
        writeOnImage($image, (!empty($template->element_labels['member_id'])) ? $template->element_labels['member_id'] : 'Member #', (int)$template->coordinates['_number']['x'], (int)$template->coordinates['_number']['y'] + $yAddition, $fontData);
        writeOnImage($image, $member->member_id, (int)$template->coordinates['_number2']['x'] + $xAdditionElements, (int)$template->coordinates['_number2']['y'] + $yAdditionElements, $fontData);
        writeOnImage($image, $memberRenewal, (int)$template->coordinates['_expiry2']['x'] + $xAdditionElements, (int)$template->coordinates['_expiry2']['y'] + $yAdditionElements, $fontData);
        $this->writeImageOnMemberCard($image, $template, $member, Member::IMAGES['PROFILE']);
        $this->writeImageOnMemberCard($image, $template, $member, Member::IMAGES['VALIDATE_QR']);
        $image = $image->encode('jpg');
        $name = $member->member_id . md5(date('d-m-y h:s:i'));
        $path = '/member-id-cards' . $member->organization_id . '/' . $name . '.jpg';

        try {
            \Storage::put($path, $image);
        } catch (\Exception $exception) {
            \Log::info($exception->getMessage());
        }

        $url = \Storage::disk('local')->url($path);

        if (!empty($member->member_id_card) && $member->member_id_card != $url) {
            \Storage::disk('local')->delete('/member-id-cards' . $member->organization_id . '/' . basename($member->member_id_card));
        }

        return $url;
    }

    /**
     * @param Image $image
     * @param OrganizationCardTemplate $template
     * @param Member $member
     * @param $fieldName
     * @return Image
     */
    public function writeImageOnMemberCard(Image $image, OrganizationCardTemplate $template, Member $member, $fieldName)
    {
        if (!empty($template->coordinates) && isset($template->coordinates[$fieldName])) {
            $width = (int)$template->coordinates[$fieldName]['width'];
            $height = (int)$template->coordinates[$fieldName]['width'];
            $x = (int)$template->coordinates[$fieldName]['x'];
            $y = (int)$template->coordinates[$fieldName]['y'];
            switch ($fieldName) {
                case Member::IMAGES['PROFILE']:
                    $url = $member->identity;
                    break;
                case Member::IMAGES['VALIDATE_QR']:
                    $url = $member->qr_code;
                    break;
            }
            if ($url) {

                /** @var Image $imageToWrite */

                $url = str_replace('https', 'http',$url);

                $imageToWrite = \Image::make($url)->resize($width, $height, function ($constraint) {
                    /** @var $constraint Constraint */
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                //region To Draw Circle of image
//            $mask = \Image::canvas($width, $height);
//            $mask->circle($width, $width/2, $height/2, function ($draw) {
//                $draw->background('#fff');
//            });
//            $imageToWrite->mask($mask, false);
                //endregion

                $image->insert($imageToWrite, 'top-left', $x, $y);  //writing qrCode to the canvas
            }

        }
        return $image;
    }

    /**
     *  This function will hard delete the member with provided id and all of its data from the application.
     *  ****** Be careful before calling this method. ******
     *
     * @param $id int primary key of members table.
     * @return bool
     */
    public function deleteMember($id)
    {
        try {
            DB::beginTransaction();

            //region Deleting Member
            $this->deleteMemberProfile($id);    //deleting member profile
            $this->deleteMemberGroup($id);      //deleting associated groups with member ( removing links )
            $this->deleteMemberCoffeeCardRewards($id);  //deleting member coffee card rewards
            $this->deleteMemberCoffeeLogs($id);     //deleting member coffee card logs
            $this->deleteMemberDirectPaymentDetail($id);        //deleting member direct payment entries
            $this->deleteMemberEducation($id);      //deleting member education records.
            $this->deleteMemberEmployments($id);    // deleting member employ ment record.
            $this->deleteMemberNotes($id);    // deleting member notes.
            $this->deleteMemberNotifications($id);  //deleting member notifications.
            $this->deleteMemberOthers($id);         //deleting member others data
            $this->deleteMemberSmsList($id);        //deleting member sms list entries
            $this->deleteMemberCoffeeCards($id);    //deleting member coffee cards
            Member::where('id', $id)->delete();     //deleting member itself.
            //endregion
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            \Log::info($exception->getMessage());
            DB::rollBack();
            return false;
        }
    }

    public function deleteMemberGroup($memberId)
    {
        GroupMember::where('member_id', $memberId)->delete();
    }

    public function deleteMemberCoffeeCardRewards($memberId)
    {
        MemberCoffeeCardReward::where('member_id', $memberId)->delete();
    }

    public function deleteMemberCoffeeLogs($memberId)
    {
        MemberCoffeeCardLog::where('member_id', $memberId)->delete();
    }

    public function deleteMemberDirectPaymentDetail($memberId)
    {
        MemberDirectPaymentDetails::where('member_id', $memberId)->delete();
    }

    public function deleteMemberEducation($memberId)
    {
        MemberEducation::where('member_id', $memberId)->delete();
    }

    public function deleteMemberEmployments($memberId)
    {
        MemberEmployment::where('member_id', $memberId)->delete();
    }

    public function deleteMemberNotifications($memberId)
    {
        MemberNotification::where('member_id', $memberId)->delete();
    }

    public function deleteMemberOthers($memberId)
    {
        MemberOther::where('member_id', $memberId)->delete();
    }

    public function deleteMemberSmsList($memberId)
    {
        SmsListMember::where('member_id', $memberId)->delete();
    }

    public function deleteMemberNotes($memberId)
    {
        Note::where('member_id', $memberId)->delete();
    }

    public function deleteMemberCoffeeCards($memberId)
    {
        MemberCoffeeCard::where('member_id', $memberId)->delete();
    }

    public function deleteMemberProfile($memberId)
    {
        MemberProfile::where('member_id', $memberId)
            ->delete();
    }

    /**
     * @param Member $member
     * @param $fieldName
     * @return string|null
     * @throws ApiException
     */
    public function updateIdCardOnFieldChange(Member $member, $fieldName)
    {

        if (in_array($fieldName, Member::REGENERATE_CARD_FIELDS) && $member->template) {
            $cardUrl = $this->generateMemberCard($member->template, $member);
        } else {
            $cardUrl = $member->member_id_card;
        }
        return $cardUrl;
    }

    /**
     * @param Member $member
     * @return Member
     * @throws ApiException
     */
    public function reGenerateMemberCard(Member $member)
    {
        if ($member->template) {
            $member->member_id_card = $this->generateMemberCard($member->template, $member);
            $member->save();
        }
        return $member;
    }

    /**
     * @param Organization $organization
     * @param $id
     * @return bool
     */
    public function verifyMemberId(Organization $organization, $id)
    {
        if (is_numeric($id)) {
            $memberCount = $organization->members()->whereMemberId($id)->count();
            if ($memberCount <= 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Updating Member fields from the notifications.
     *
     * @param MemberNotification $notification
     * @return void
     */
    public function updateNotificationField(MemberNotification $notification)
    {
        /** @var Member $member */
        $member = $notification->member;
        if ($member) {
            foreach ($notification->changed_fields as $changed_field) {
                try {
                    $fieldName = $changed_field->field_name;
                } catch (\Exception $exception) {
//                    dd($exception->getMessage());
                }

                if (!empty($fieldName)) {

                    if (in_array($fieldName, ['country', 'address', 'postal_code', 'city'])) {
                        $physicalAddress = $member->physicalAddress;
                        if (empty($physicalAddress)) {
                            $physicalAddress = new Address();
                            $physicalAddress->address_type_id = AddressType::PHYSICAL_ADDRESS;
                        }
                        switch ($fieldName) {
                            case 'country':
                                $country = Country::whereName($changed_field->new_value)->first();
                                $physicalAddress->country_id = (    $country) ? $country->id : $physicalAddress->country_id;
                                break;
                            case 'address':
                                $physicalAddress->address1 = ($changed_field->new_value) ? $changed_field->new_value : $physicalAddress->address1;
                                break;
                            case 'city':
                                $physicalAddress->city = ($changed_field->new_value) ? $changed_field->new_value : $physicalAddress->city;
                                break;
                            case 'postal_code':
                                $physicalAddress->postal_code = ($changed_field->new_value) ? $changed_field->new_value : $physicalAddress->postal_code;
                                break;
                        }
                        $physicalAddress->item_type_id = AddressType::MEMBER_PROFILE;
                        $physicalAddress->save();
                        $member->physical_address_id = $physicalAddress->id;
                        $member->save();
                    } else {
                        if ($fieldName == 'date_of_birth') {  //if field name is birthday then format the date and save it to the database.
                            $dashedDob = str_replace('/', '-', $changed_field->new_value);
                            $dateToMatch = date('Y-m-d', strtotime($dashedDob));
                            $member->$fieldName = $dateToMatch;
                        } else {          //save all other fields updated in the notification.
                            $member->$fieldName = $changed_field->new_value;
                        }
                    }
                }
            }
            $member->details_updated_date_time = Carbon::now();
            $member->save();
            $user = ApiHelper::getApiUser();
            $notification->is_updated = IStatus::ACTIVE;
            $notification->updated_date_time = Carbon::now();
            if ($user) {
                $notification->updated_by_user_id = $user->id;
            }
            $notification->save();
        }
    }

    /**
     * Add member profile at first, when adding a member.
     * @param Member $newMember
     * @param array $data
     * @return MemberProfile|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function addMemberProfile(Member $newMember, $data = [])
    {
        $memberProfile = MemberProfile::whereMemberId($newMember->id)->first();
        if (empty($memberProfile)) {
            $memberProfile = new MemberProfile();
        }

        $memberProfile->fill($newMember->toArray());

        $memberProfile->contact_no = array_get($data, 'contact_no');
        $memberProfile->country_code = array_get($data, 'country_code');
        if (empty($data['country_code'])) {
            try {
                $memberProfile = $this->resolveCountryCodeAndMobileNumber($memberProfile, array_get($data, 'contact_no'));
            } catch (\Exception $exception) {
                \Log::info('There is issue with resolving contact no: ' . array_get($data, 'contact_no'), $exception->getMessage());
            }
        }

        if (!empty(array_get($data, 'password'))) {
            $memberProfile->password = md5(array_get($data, 'password'));
        }

        $memberProfile->member_id = $newMember->id;
        $memberProfile->save();

        return $memberProfile;
    }

    /**
     * Contact list query for Datatable listing.
     * @param Organization $organization
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getContactList(Organization $organization, $data = [])
    {
        // Contact fields to show in the contact response from members table.
        $fieldsToLoad = [
            'id',
            'first_name',
            'last_name',
            'middle_name',
            'next_of_kin',
            'next_of_kin_contact_no',
            'contact_no',
            'organization_id',
            'physical_address_id',
            'postal_address_id',
            'details_updated_date_time',
            'joining_date',
            'email',
            'known_as',
            'title',
            'facebook_id',
            'gender',
            'phone',
            'status',
            'subscription',
            'financial',
            'due',
            'renewal',
            'identity',
            'payment_frequency',
            'payment_method',
            'parent_code'

        ];

        //With Relations
        $withRelations = [
            'organization' => function ($query) {
                $query->select('id', 'name');
                $query->with(['smsSetting' => function ($q) {
                    $q->select('id');
                }]);
            },
            'physicalAddress',
            'postalAddress',
            'groups',
            'notes' => function ($query) {
                $query->select('id', 'title', 'description', 'user_id', 'created_at', 'updated_at', 'member_id');
                $query->with(['user' => function ($q) {
                    $q->select('id', 'first_name', 'last_name');
                }]);
            }
        ];

        return $organization->contacts()->select($fieldsToLoad)->orderBy('members.first_name', 'ASC')->with($withRelations);
    }

    /**
     * This function will be called when a contact will be converted into the member.
     * Change status to Pending new, Assign Auto assign subscription, change type, assign member number.
     * @param Organization $organization
     * @param array $data
     * @return Member
     * @throws ApiException
     */
    public function convertContactToMember(Organization $organization, $data = [])
    {
        /** @var Member $contact */
        $contact = $organization->contacts()->where('members.id', array_get($data, 'id'))->first();

        if (!$contact) {
            throw new ApiException(null, ['error' => 'Unable to find contact']);      // send api response that contact isn't found.
        }

        /** @var Subscription $subscription */
        $subscription = $organization->subscriptions()
            ->where('auto_assign', IStatus::ACTIVE)
            ->where('subscriptions.status', IStatus::ACTIVE)
            ->first();

        if ($subscription)
            $this->addSubscription($contact, $subscription);

        $contact->member_id = $this->generateNextMemberNo($organization->id); // get next member number in organization details  or 1
        $contact->member_id = $this->findUnmatchedNumber($contact->member_id, $organization->id);

        $this->saveNextMember($organization->id);    //saving next member number into the organization details.

        $contact->status = IStatus::PENDING_NEW;
        $contact->type = Member::TYPE['MEMBER'];
        $contact->details_updated_date_time = Carbon::now();

        /** @var OrganizationCardTemplate $backgroundTemplate */
        $backgroundTemplate = $organization->templates()->first();
        if($backgroundTemplate){
            $contact->organization_card_template_id = $backgroundTemplate->id;
            $contact->member_id_card = $this->generateMemberCard($backgroundTemplate,$contact);
        }

        $contact->save();

        \App\MemberOther::firstOrCreate(['member_id' => $contact->id]); //creating member other entry.

        return $contact;
    }

    /**
     * @param Member $member
     * @param bool $save
     * @return Member
     */
    public function addDetailUpdatedDateTime(Member $member, $save = true)
    {
        $member->details_updated_date_time = Carbon::now();
        if($save){
            $member->save();
        }
        return $member;
    }

    public function getPosClientCardName(Organization $organization)
    {
        try {
            $untillService = new UntillService($organization);
            $cardNames = $untillService->getCardNames();
            if(is_array($cardNames)){
                $this->updateMemberCardNames($organization, $cardNames);
            }
        } catch (ApiException $e) {
            \Log::info('Unable to fetch Cards From the Pos');
            //todo discuss with client, Either o show this or not.
            throw new ApiException([],null,'something wrong with until service');
        }
    }

    /**
     * @param $posCardNames array
     */
    public function updateMemberCardNames(Organization $organization, $posCardNames = [])
    {
        foreach ($posCardNames as $posCardName) {
            $this->updateMemberCardName($organization, $posCardName);
        }
    }

    /**
     * @param Organization $organization
     * @param $posCardName
     */
    public function updateMemberCardName(Organization $organization,$posCardName)
    {
        Record::updateOrCreate([
            'name' => array_get($posCardName,'Name'),
            'record_type_id' => IRecordType::POS_CLIENT_CARD_NAME,
            'organization_id' => $organization->id
            ],
            [
                'name' => array_get($posCardName,'Name'),
                'record_type_id' => IRecordType::POS_CLIENT_CARD_NAME,
                'organization_id' => $organization->id,
                'data' => [
                    'value' => array_get($posCardName,'Id'),
                ],
            ]
        );
    }

    /**
     * @param $member
     * @param $key
     * @param $value
     */
    public function updateUntillData($member, $key,$value)
    {
        $untillData = $member->untill_data;
        $untillData[$key] = $value;
        $member->untill_data = $untillData;
        $member->save();
    }

    /**
     * @param Member $member
     * @param $clientCards
     */
    public function savePosClientCards(Member $member, $clientCards)
    {
        $untillData = $member->untill_data;
        foreach ($clientCards as $clientCard) {
            $clientCardNameId = array_get($clientCard, 'CardNameId');
            $untillData['client_cards'][$clientCardNameId] = array_get($clientCard,'Id');
        }
        $member->untill_data = $untillData;
        $member->save();
    }

    /**
     * @param $fieldName
     * @param $fieldValue
     * @return bool
     */
    public function checkOthersUnique($fieldName, $fieldValue, $organizationId)
    {
        if(in_array($fieldName,MemberOther::AuthorizedFields())){
            $memberOther = MemberOther::where($fieldName, $fieldValue)
                ->join('members', 'members.id', '=', 'member_others.member_id')
                ->where('members.organization_id','=',$organizationId )->first();
            if($memberOther){
                return false;
            }
            return true;
        }
    }

    /**
     * @param Organization $organization
     * @param Member $member
     */
    public function pushCardsToPos(Organization $organization, Member $member)
    {
        if(!empty($member->validate_id)){
            try{
                $this->pushCardToPos($member,$organization,Member::CARD_NAME['MEMBERME_ID'],$member->validate_id);
            }catch (\Exception $exception){
                \Log::info('Unable to push card  '.Member::CARD_NAME['MEMBERME_ID'] .' of member #: '. $member->id. '| Exception:'.$exception->getMessage());
            }
        }
        $memberOther = $member->others;
        if($memberOther){
            if(!empty($memberOther->prox_card)){
                try{
                    $this->pushCardToPos($member,$organization,Member::CARD_NAME['PROX_CARD'],$memberOther->prox_card);
                }catch (\Exception $exception){
                    \Log::info('Unable to push card  '.Member::CARD_NAME['MEMBERME_ID'] .' of member #: '. $member->id. '| Exception:'.$exception->getMessage());
                }
            }
            if(!empty($memberOther->swipe_card)){
                try {
                    $this->pushCardToPos($member, $organization, Member::CARD_NAME['SWIPE_CARD'], $memberOther->swipe_card);
                } catch (\Exception $exception) {
                    \Log::info('Unable to push card  '.Member::CARD_NAME['MEMBERME_ID'] .' of member #: '. $member->id. '| Exception:'.$exception->getMessage());

                }
            }
        }
    }

    /**
     * @param Organization $organization
     * @param $memberId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMemberViewLog(Organization $organization, $memberId)
    {
        return $organization->memberViewLogs()->where('member_id',$memberId)->with([
            'user' => function($query){
                $query->select('id','first_name','last_name');
            },
        ])->orderBy('view_time','desc')->groupBy('view_time')->get();
    }

    /**
     * @param Organization $organization
     * @param array $data
     * @return MemberViewLog|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function addMemberViewLog(Organization $organization, $data = [])
    {
        $createdLog = MemberViewLog::create(['organization_id' => $organization->id ,'member_id' => array_get($data,'member_id'),'view_time' => Carbon::now()]);
        return MemberViewLog::whereId($createdLog->id)->with([
            'user' => function($query){
                $query->select('id','first_name','last_name');
            },
        ])->first();
    }

    /**
     * @param Organization $organization
     * @param $members
     */
    public function pushSavePointsToPos(Organization $organization, $members)
    {
        foreach ($members as $member) {
            write_import_logs($organization->name. 'Points Push',[$member->full_name, $member->member_id],false, 'Pushing Points');
            $this->pushMemberSavePointsToPos($organization,$member);
        }
    }

    /**
     * @param Organization $organization
     * @param Member $member
     */
    public function pushMemberSavePointsToPos(Organization $organization, Member $member)
    {
        $savePoints =  (!empty($member->others))?$member->others->points: null;
        if($savePoints && $member->untill_id){
            try {
                $untillService = new UntillService($organization);
            } catch (ApiException $e) {
                \Log::error('Unable to setup untill Setting');
            }

            $untillService->pushSavePoints($member->untill_id,$savePoints);
        }
    }

    /**
     * @param MemberCoffeeCard $memberCoffeeCard
     * @throws \Exception
     */
    public function removeMemberCoffeeCard(MemberCoffeeCard $memberCoffeeCard)
    {
        $memberCoffeeCard->memberCoffeeCardLog()->delete();
        $memberCoffeeCard->delete();
    }

    /**
     * @param Organization $organization
     * @param array $data
     */
    public function updateMemberPoints(Organization $organization, $data = [])
    {
        /** @var Member $member */
        $member = $organization->members()->where('untill_id',array_get($data,'client_id'))->first();
        if($member){
            /** @var MemberOther $memberOthers */
            $memberOthers = $member->others;
            if($memberOthers){
                $memberOthers->points = array_get($data,'points');
                $memberOthers->save();
            }
        }
    }

    /**
     * @throws ApiException
     */
    public function getUntillExClient()
    {
        $untillService = new UntillService(Organization::find(15636));
        $response = $untillService->getInActiveClients();
        dd($response);
    }

    /**
     * @param Organization $organization
     * @param Member $member
     * @return mixed|null
     * @throws ApiException
     */
    public function getUpdatedMemberPoints(Organization $organization, Member $member)
    {
        $untillService = new UntillService($organization);
        $response = $untillService->getClientById($member->untill_id);
        if(!empty($response)){
            return array_get($response,'SavePoints');
        }
        return null;
    }

    public function getMemberChangeLogs(Member $member, $data = [])
    {
        $result = [];
//        $page = array_get($data,'page',1);
//        $limit =  array_get($data,'limit',10);
//        $firstRecord = $page * $limit - $limit;
//        $totalCount = $member->changeLogs()->count();
//        $lastRecord = ( $firstRecord + $limit >= $totalCount ) ? $totalCount : $firstRecord + $limit;
//
//        $result['total_count'] = $totalCount;
//        $result['first_record'] = $firstRecord;
//        $result['last_record'] = $lastRecord;
        $result['data'] = $member->changeLogs()
            ->whereNotIn('user_id',[1])
            ->with([
                'user' => function($query){
                    $query->select('id','first_name','last_name');
                }
            ])
//            ->skip($firstRecord-1)
//            ->limit($limit)
                ->limit(200)
            ->orderBy('id','desc')
            ->get();

        return $result;
    }
}

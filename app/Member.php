<?php

namespace App;

use App\base\IStatus;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Role;

/**
 * App\Member
 *
 * @property int $id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $contact_no
 * @property string|null $date_of_birth
 * @property string|null $parent_code
 * @property string|null $email
 * @property string|null $title
 * @property string|null $facebook_id
 * @property string|null $known_as
 * @property string|null $gender
 * @property string|null $phone
 * @property string|null $password
 * @property int|null $physical_address_id
 * @property int|null $postal_address_id
 * @property int|null $status
 * @property int|null $type
 * @property string|null $last_login
 * @property string|null $subscription
 * @property MemberEmployment $employment
 * @property MemberEducation $education
 * @property int|null $financial
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereContactNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereFacebookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereFinancial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereKnownAs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereLastLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereParentCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member wherePhysicalAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member wherePostalAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereSubscription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereUpdatedAt($value)
 * @property string|null $middle_name
 * @property int $organization_id
 * @property string|null $member_id
 * @property string|null $validate_id
 * @property int|null $organization_card_template_id
 * @property int|null $subscription_id
 * @property string|null $renewal
 * @property string|null $identity
 * @property string|null $subscription_start_date
 * @property string|null $api_token
 * @property string|null $password_sent_date_time
 * @property string|null $password_change_date_time
 * @property int|null $due
 * @property string|null $verify_token
 * @property int|null $verify
 * @property string|null $next_of_kin
 * @property string|null $next_of_kin_contact_no
 * @property string|null $payment_method
 * @property string|null $payment_frequency
 * @property string|null $joining_date
 * @property string|null $subscription_assign_date
 * @property float|null $payment_amount
 * @property float|null $payments_to_make
 * @property float|null $payments_remaining
 * @property string|null $next_payment_date
 * @property float|null $owing
 * @property string|null $details_updated_date_time
 * @property mixed $physical_address
 * @property mixed $postal_address
 * @property mixed $direct_payment_details
 * @property mixed $sms_list_member
 * @property mixed $draw_entries
 * @property-read \App\MemberDirectPaymentDetails $directPaymentDetails
 * @property-read mixed $default_country_code
 * @property-read mixed $joining_fee
 * @property-read mixed $sent_messages
 * @property-read mixed $subscription_fee
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Group[] $groups
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Note[] $notes
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \App\Organization $organization
 * @property-read \App\MemberOther $others
 * @property-read \App\Address|null $physicalAddress
 * @property-read \App\Address|null $postalAddress
 * @property-read \App\MemberProfile $profile
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SmsListMember[] $smsListMember
 * @property-read \App\OrganizationCardTemplate|null $template
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member notExpired()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member notResigned()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereApiToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereDetailsUpdatedDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereDue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereIdentity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereJoiningDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereNextOfKin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereNextOfKinContactNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereNextPaymentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereOrganizationCardTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereOwing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member wherePasswordChangeDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member wherePasswordSentDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member wherePaymentAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member wherePaymentFrequency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member wherePaymentsRemaining($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member wherePaymentsToMake($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereRenewal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereSubscriptionAssignDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereSubscriptionStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereValidateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereVerify($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereVerifyToken($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member query()
 * @property-read \App\DrawEntry $drawEntries
 * @property-read \App\Subscription $memberSubscription
 * @property string|null $full_name
 * @property mixed $coffee_card_rewards
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereFullName($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\MemberCoffeeCard[] $memberCoffeeCard
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\MemberCoffeeCardReward[] $coffeeCardRewards
 * @property string|null $member_id_card
 * @property mixed $member_coffee_card
 * @property mixed $coffee_card_log
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereMemberIdCard($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\MemberCoffeeCardLog[] $coffeeCardLog
 * @property string|null $untill_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereUntillId($value)
 * @property string|null $qr_code
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereQrCode($value)
 * @property int|null $is_imported
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Member whereIsImported($value)
 * @property string|null $untill_data
 * @property-read int|null $coffee_card_log_count
 * @property-read int|null $coffee_card_rewards_count
 * @property-read int|null $education_count
 * @property-read int|null $employment_count
 * @property-read int|null $groups_count
 * @property-read int|null $member_coffee_card_count
 * @property-read int|null $notes_count
 * @property-read int|null $notifications_count
 * @property-read int|null $sms_list_member_count
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereUntillData($value)
 * @property int|null $ethnicity
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ChangeLog[] $changeLogs
 * @property-read int|null $change_logs_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\MemberViewLog[] $memberViewLogs
 * @property-read int|null $member_view_logs_count
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereEthnicity($value)
 */
class Member extends Authenticatable
{

    use Notifiable;

    protected $fillable = [
        'first_name', 'last_name', 'contact_no', 'date_of_birth', 'organization_id', 'member_id', 'email','known_as','parent_code','gender', 'title', 'middle_name','facebook_id','phone', 'status','due','financial','joining_date',
        'subscription_start_date', 'last_login','validate_id', 'type'
    ];


    protected $appends = ['subscription_fee', 'joining_fee', 'default_country_code', 'sent_messages'];

    const POS_FIELDS = [
        'first_name','last_name', 'full_name', 'date_of_birth', 'contact_no', 'email', 'swipe_card', 'address', 'prox_card', 'renewal'
    ];

    const REGENERATE_CARD_FIELDS = [
        'first_name','last_name','full_name','subscription_id','renewal','identity','validate_id',
    ];

    const NON_LOGS_FIELDS = [
        'id','created_at','updated_at','details_updated_date_time','api_token','password','full_name', 'physical_address_id','postal_address_id','subscription_id',
    ];
    const FINANTIAL = [
        'PAID'
    ];

    const CARD_NAME = [
        'MEMBERME_ID' => 'Memberme ID',
        'PROX_CARD' => 'Prox Card ID',
        'SWIPE_CARD' => 'Swipe Card ID',
    ];

    public static function getClientCardNames()
    {
        return [self::CARD_NAME['MEMBERME_ID'],self::CARD_NAME['PROX_CARD'],self::CARD_NAME['SWIPE_CARD']];
    }

    const IMPORT_ERROR_MESSAGE = [
        'INVALID_ORGANIZATION' => 'Invalid Organization Number',
        'INVALID_SUBSCRIPTION' => 'Invalid Subscription',
        'INVALID_PROPOSER_ID' => 'Invalid Proposer Id',
        'INVALID_PARENT_NUMBER' => 'Invalid Parent Number',
        'MEMBER_WITH_SAME_EMAIL_EXISTS' => 'Member with same email already exists',
        'MEMBER_WITH_SAME_ID_EXISTS' => 'Member with same id is already exists',
        'INVALID_SECONDARY_NUMBER' => 'Invalid Secondary Number',
        'INVALID_EMAIL' => 'Invalid email. Please make sure email is valid or empty',
        'INVALID_PHYSICAL_COUNTRY' => 'Could not find any physical country from this name',
        'INVALID_POSTAL_COUNTRY' => 'Could not find any postal country from this name',
    ];

    const AUTHORISED_FIELDS = [
        'first_name',
        'middle_name',
        'last_name',
        'contact_no',
        'date_of_birth',
        'organization_id',
        'member_id',
        'validate_id',
        'email',
        'title',
        'facebook_id',
        'known_as',
        'gender',
        'phone',
        'status',
        'subscription',
        'financial',
        'due',
        'renewal',
        'identity',
        'subscription_start_date',
        'payment_frequency',
        'payment_method',
        'ethnicity'
    ];

    const ADDRESS_FIELDS = [
        'address',
        'suburb',
        'postal_code',
        'city',
        'region',
    ];

    const Authorised_operators = [
        'is_yes' => 'Is Yes',
        'is_no' => 'Is No',
        'begin_with' => 'Begin With',
        'ends_in' => 'Ends in',
        'contains' => 'Contains',
        'not like' => 'Not Contains',
        '=' => 'Equals',
        '<' => 'Less then',
        '<=' => 'Less then or equal',
        '!=' => 'Not Equals'
    ];

    const IMAGES = [
        'PROFILE' => 'profile_image',
        'VALIDATE_QR' => 'qrCode',
        'PROX_CODE_QR' => 'prox_code_qr'
    ];

    const TYPE = [
        'MEMBER' => 1,
        'CONTACT' => 2,
    ];

    /**
     * Return the field names for this table.
     * @return array
     */
    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
    /**
     *  Return the saperate profile of member if exist.
     *
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile()
    {
        return $this->hasOne(MemberProfile::class, 'member_id', 'id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function physicalAddress()
    {
        return $this->belongsTo(Address::class, 'physical_address_id', 'id');
    }

    public function postalAddress()
    {
        return $this->belongsTo(Address::class, 'postal_address_id', 'id');
    }

    public function template()
    {
        return $this->belongsTo(OrganizationCardTemplate::class, 'organization_card_template_id', 'id');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    public function others()
    {
        return $this->hasOne(MemberOther::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function employment()
    {
        return $this->hasMany(MemberEmployment::class);
    }

    public function memberSubscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function education()
    {
        return $this->hasMany(MemberEducation::class);
    }
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function smsListMember()
    {
        return $this->hasMany(SmsListMember::class);
    }

    public function drawEntries()
    {
        return $this->belongsTo(DrawEntry::class);
    }
    public function directPaymentDetails()
    {
        return $this->hasOne(MemberDirectPaymentDetails::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function coffeeCardRewards()
    {
        return $this->hasMany(MemberCoffeeCardReward::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function memberCoffeeCard()
    {
        return $this->hasMany(MemberCoffeeCard::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function coffeeCardLog()
    {
        return $this->hasMany(MemberCoffeeCardLog::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function memberViewLogs()
    {
        return $this->hasMany(MemberViewLog::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function changeLogs()
    {
        return $this->hasMany(ChangeLog::class,'entity_id','id')->where('model' , Member::class);
    }

// =============================================================================== Relation ending. ======================================================
    public function getSubscriptionFeeAttribute()
    {
        if (!empty($this->subscription)) {
            $subscription = Subscription::find($this->subscription_id);
            if ($subscription)
                return $subscription->subscription_fee;
        }
        return null;
    }

    public function getJoiningFeeAttribute()
    {
        if (!empty($this->subscription)) {
            $subscription = Subscription::find($this->subscription_id);
            if ($subscription)
                return $subscription->joining_fee;
        }
        return null;
    }

    public function getDefaultCountryCodeAttribute()
    {
        if (!empty($this->organization->smsSetting->country_code)) {
            return $this->organization->smsSetting->country_code;
        }
        return '64';
    }

    public function getSentMessagesAttribute()
    {
        $messages = [];
        if(!empty($this->smsListMember)){
            $smsListMembers = \DB::table('sms_list_members')
                ->join('sms_lists','sms_lists.id','=','sms_list_members.sms_list_id')
                ->join('list_sent_sms','list_sent_sms.sms_list_id','=','sms_lists.id')
                ->where('sms_list_members.member_id',$this->id)
                ->orderBy('list_sent_sms.id','desc')
                ->select('list_sent_sms.message','list_sent_sms.created_at as sent_date_time')
                ->groupBy('list_sent_sms.created_at')
                ->get()
                ->toArray();
            $messages = $smsListMembers;
        }
        return $messages;
    }

    public function getSubscriptionAttribute()
    {
        if($this->subscription_id){
            $subscription = $this->subscription()->first();
            if(!empty($subscription))
                return $subscription->title;
        }
        return null;
    }

//    ---------------------------------------- Mutators -----------------------------------------------
    public function setUntillDataAttribute($value)
    {
        $this->attributes['untill_data'] = serialize_data($value);

    }

    public function getUntillDataAttribute()
    {
        if ($this->attributes['untill_data']) {
            return un_serialize_data($this->attributes['untill_data']);
        }
        return null;
    }

//    ---------------------------------------- Scopes --------------------------------------------------
    public function scopeNotResigned($query)
    {
        $query->where('status' ,'!=', IStatus::RESIGNED);
    }

    public function scopeNotExpired($query)
    {
        $query->where('status', '!=', IStatus::EXPIRED);
    }
}

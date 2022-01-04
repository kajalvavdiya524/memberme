<?php

namespace App;

use App\base\IStatus;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Role;
/**
 * App\Organization
 *
 * @property int $id
 * @property int $organization_id
 * @property string|null $bio
 * @property string $contact_name
 * @property string $contact_email
 * @property string $contact_phone
 * @property string|null $office_phone
 * @property int $industry
 * @property int $timezone_id
 * @property string $api_token
 * @property string $password
 * @property int $status
 * @property int $current
 * @property int $name
 * @property int $user_id
 * @property int $plan_id
 * @property string $account_no
 * @property string|null $logo
 * @property string|null $cover
 * @property string|null $physical_address_id
 * @property string|null $postal_address_id
 * @property string|null $gst_number
 * @property string|null $starting_member
 * @property string|null $starting_receipt
 * @property string|null $next_member
 * @property string|null $data
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Address|null $physicalAddress
 * @property-read \App\Address|null $postalAddress
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail whereAccountNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail whereContactEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail whereContactName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail whereContactPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail whereCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail whereGstNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail whereIndustry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail whereNextMember($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail whereOfficePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail wherePhysicalAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail wherePostalAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail whereStartingMember($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail whereStartingReceipt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationDetail whereUpdatedAt($value)
 * @property string|null $verify_token
 * @property string|null $remember_token
 * @property string|null $plan_expiry
 * @property-read \App\OrganizationDetail $details
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Group[] $groups
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\KioskBackground[] $kioskBackgrounds
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\OrganizationKioskTemplate[] $kioskTemplates
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Kiosk[] $kiosks
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\MemberNotification[] $memberNotifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Member[] $members
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Note[] $notes
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \App\OrganizationOption $options
 * @property-read \App\OrganizationSetting $organizationSettings
 * @property-read \App\User|null $owner
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PaymentType[] $paymentTypes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Payment[] $payments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Receipt[] $receipts
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Role[] $roles
 * @property-read \App\OrganizationSmsSetting $smsSetting
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Subscription[] $subscriptions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\OrganizationCardTemplate[] $templates
 * @property-read \App\Timezone|null $timezone
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Transaction[] $transactions
 * @property-read \App\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereApiToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereCurrent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization wherePlanExpiry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization wherePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereTimezoneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereVerifyToken($value)
 * @mixin \Eloquent
 * @property int|null $plan_payment_status
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization wherePlanPaymentStatus($value)
 * @property string|null $stripe_customer_id
 * @property mixed $stripe_subscription
 * @property mixed $draws
 * @property mixed $advertising
 * @property mixed $voucherlogs
 * @property mixed $draw_entries
 * @property mixed $kiosk_voucher_parameter
 * @property mixed $coffee_cards
 * @property mixed $member_coffee_card_log
 * @property-read \App\Plan|null $plan
 * @property-read \App\StripeSubscription|null $stripeSubscription
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereStripeCustomerId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Voucher[] $voucher
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization query()
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\VoucherParameter[] $voucherParameters
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Voucher[] $vouchers
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DrawEntry[] $drawEntries
 * @property-read \App\KioskVoucherParameter $kioskVoucherParameter
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CoffeeCard[] $coffeeCards
 * @property string $scanner_token
 * @property mixed $sendgrid_settings
 * @property mixed $email_templates
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\MemberCoffeeCardLog[] $memberCoffeeCardLog
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereScannerToken($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\EmailTemplate[] $emailTemplates
 * @property-read \App\SendgridSetting $sendgridSetting
 * @property-read \App\UntillSetting $untillSetting
 * @property-read \App\OfficeUse $officeUse
 * @property string|null $last_login
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereLastLogin($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\UserLastLogin[] $lastLogins
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\KioskBackground[] $specificBackgrounds
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Member[] $contacts
 * @property-read int|null $advertising_count
 * @property-read int|null $coffee_cards_count
 * @property-read int|null $contacts_count
 * @property-read int|null $draw_entries_count
 * @property-read int|null $draws_count
 * @property-read int|null $email_templates_count
 * @property-read int|null $groups_count
 * @property-read int|null $kiosk_backgrounds_count
 * @property-read int|null $kiosk_templates_count
 * @property-read int|null $kiosks_count
 * @property-read int|null $last_logins_count
 * @property-read int|null $member_coffee_card_log_count
 * @property-read int|null $member_notifications_count
 * @property-read int|null $members_count
 * @property-read int|null $notes_count
 * @property-read int|null $notifications_count
 * @property-read int|null $payment_types_count
 * @property-read int|null $payments_count
 * @property-read int|null $receipts_count
 * @property-read int|null $roles_count
 * @property-read int|null $specific_backgrounds_count
 * @property-read int|null $subscriptions_count
 * @property-read int|null $templates_count
 * @property-read int|null $transactions_count
 * @property-read int|null $voucher_parameters_count
 * @property-read int|null $voucherlogs_count
 * @property-read int|null $vouchers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\MemberViewLog[] $memberViewLogs
 * @property-read int|null $member_view_logs_count
 */

class Organization extends Authenticatable
{
    use Notifiable;

    const NAME = 'organization';

    protected  $hidden = [
        'password','api_token','verify_token'
    ];

    protected $fillable = [
        'address_type_id',
    ];

    //====================================================== Relations =================

    /**
     * Return The Creator of this organization
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner(){
        return $this->belongsTo('App\User','user_id' , 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Return the Organization Details instance  for this Organization
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function details(){
        return $this->hasOne('App\OrganizationDetail','organization_id','id');
    }

    /**
     * Return the list of all groups which are related to this organization
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groups()
    {
        return $this->hasMany(Group::class,'organization_id','id');
    }


    /**
     * Return all the Roles belongs to this organization
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(){
        return $this->belongsToMany('\App\Role','role_user','organization_id','id');
    }

    /**
     * Return All the members of this organization
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function members()
    {
        return $this->hasMany(Member::class)->where('type' , Member::TYPE['MEMBER']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contacts()
    {
        return $this->hasMany(Member::class)->where('type' , Member::TYPE['CONTACT']);
    }

    /**
     * return all the subscriptions
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Return all written notes to member by this organization
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    /**
     * Return All the templates for this organizations
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function templates()
    {
        return $this->hasMany(OrganizationCardTemplate::class);
    }

    /**
     * Return all kiosk Templates.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function kioskTemplates()
    {
        return $this->hasMany(OrganizationKioskTemplate::class);
    }

    /**
     * Return Data of Organization Table
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function options()
    {
        return $this->hasOne(OrganizationOption::class);
    }

    /**
     * Return all payments of this organization
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function untillSetting()
    {
        return $this->hasOne(UntillSetting::class);
    }

    /**
     * Return global object of organization setting.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function organizationSettings()
    {
        return $this->hasOne(OrganizationSetting::class);
    }
    /**
     * return all transactions
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class,'organization_id','id');
    }

    /**
     * return all receipts
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

    /**
     * return all payment types for this organizations
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function paymentTypes()
    {
        return $this->hasMany(PaymentType::class);
    }

    /**
     * return the timezone of this organization
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function timezone(){
        return $this->belongsTo(Timezone::class);
    }

    public function smsSetting()
    {
        return $this->hasOne(OrganizationSmsSetting::class);
    }

    public function kioskBackgrounds()
    {
        return $this->belongsToMany(KioskBackground::class);
    }

    public function specificBackgrounds()
    {
        return $this->hasMany(KioskBackground::class);
    }

    public function kiosks()
    {
        return $this->hasMany(Kiosk::class);
    }
    public function memberNotifications()
    {
        return $this->hasMany(MemberNotification::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function stripeSubscription()
    {
        return $this->hasOne(StripeSubscription::class);
    }

    public function kioskVoucherParameter()
    {
        return $this->hasOne(KioskVoucherParameter::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function draws()
    {
        return $this->hasMany(Draw::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function drawEntries()
    {
        return $this->hasMany(DrawEntry::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function advertising()
    {
        return $this->hasMany(Advertising::class);
    }


    public function vouchers(){
        return $this->hasMany(Voucher::class);

    }
    public function voucherlogs(){
        return $this->hasMany(VoucherLog::class);

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sendgridSetting()
    {
        return $this->hasOne(SendgridSetting::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function emailTemplates()
    {
        return $this->hasMany(EmailTemplate::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function voucherParameters(){
        return $this->belongsToMany(VoucherParameter::class,'organization_voucher_parameter','organization_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function coffeeCards()
    {
        return $this->belongsToMany(CoffeeCard::class);
    }

    /*** @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function memberCoffeeCardLog()
    {
        return $this->hasMany(MemberCoffeeCardLog::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function officeUse()
    {
        return $this->hasOne(OfficeUse::class);
    }

    public function lastLogins()
    {
        return $this->hasMany(UserLastLogin::class);
    }

    public function memberViewLogs()
    {
        return $this->hasMany(MemberViewLog::class);
    }

//================================================================== End relations ========================

    public function getDataAttribute()
    {
        if(isset($this->attributes['data']) && !empty($this->attributes['data'])){
            return json_decode(unserialize($this->attributes['data']));
        }else{
            return null;
        }
    }

    public function setDataAttribute($value)
    {
        if($value != null && is_array($value)){
            $this->attributes['data'] = serialize(json_encode($value));
        }
    }
}

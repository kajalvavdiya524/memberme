<?php

namespace App;

use App\base\IStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/*
 * @property $due_duration
 * @property $joining_fee
 * @property $title
 * @property $due
 * @property $overdue
 * $property $pro_rata
 * $property $subscription_fee
 * $property $expires
 * $property $expiry_duration
 * $property $expiry_term
 * $property $expiry
 * $property $overdue_duration
 * $property $overdue_term
 * $property $amount
 * $property $frequency
 * $property $late_payment
 * $property $late_payment_duration
 * $property $late_payment_term
 * $property $late_fee
 * $property $auto_assign
 * $property $payment_reminder
 * $property $send_invoice
 * $property $payment_reminder_term
 * $property $send_invoice_date
 * $property $pro_rata_date
 * $property $overdue_fee
 * $property $overdue_days
 * $property $renewal_date_term
 * @property $expiry_date_option
 * $property $start_date_term
 * $property $expiry_quantity
 * $property $pro_rata_birthday
 * $property $id
 * $property $created_at
 * $property $updated_at
 *
 * */
/**
 * App\Subscription
 *
 * @property int $id
 * @property int $organization_id
 * @property string|null $title
 * @property float|null $joining_fee
 * @property float|null $subscription_fee
 * @property int|null $expires
 * @property int|null $expiry_duration
 * @property string|null $expiry_term
 * @property string|null $expiry
 * @property int|null $overdue
 * @property int|null $overdue_duration
 * @property string|null $overdue_term
 * @property int|null $pro_rata
 * @property int|null $amount
 * @property string|null $frequency
 * @property int|null $late_payment
 * @property int|null $late_payment_duration
 * @property string|null $late_payment_term
 * @property float|null $late_fee
 * @property string|null $role
 * @property int|null $role_id
 * @property int|null $status
 * @property string|null $data
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int|null $auto_assign
 * @property int|null $payment_reminder
 * @property string|null $payment_reminder_term
 * @property int|null $send_invoice
 * @property string|null $send_invoice_date
 * @property string|null $pro_rata_date
 * @property int|null $overdue_days
 * @property float|null $overdue_fee
 * @property int|null $due_duration
 * @property string|null $deleted_at
 * @property int|null $expiry_quantity
 * @property string|null $pro_rata_birthday
 * @property string|null $expiry_date_option
 * @property mixed $email_templates
 * @property-read mixed $due_amount
 * @property-read int $due
 * @property-read int $last_ninty_day_member_count
 * @property-read int $last_sixty_day_member_count
 * @property-read int $last_thirty_day_member_count
 * @property-read int $member_count
 * @property-read int $over_due
 * @property-read int $total
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Member[] $members
 * @property-read \App\Organization $organization
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Receipt[] $receipts
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Transaction[] $transactions
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Subscription onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereAutoAssign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereDueDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereExpires($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereExpiry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereExpiryDateOption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereExpiryDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereExpiryQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereExpiryTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereFrequency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereJoiningFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereLateFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereLatePayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereLatePaymentDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereLatePaymentTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereOverdue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereOverdueDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereOverdueDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereOverdueFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereOverdueTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription wherePaymentReminder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription wherePaymentReminderTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereProRata($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereProRataBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereProRataDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereSendInvoice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereSendInvoiceDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereSubscriptionFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Subscription withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Subscription withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription query()
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\EmailTemplate[] $emailTemplates
 * @property-read int|null $email_templates_count
 * @property-read int|null $members_count
 * @property-read int|null $receipts_count
 * @property-read int|null $transactions_count
 */
class Subscription extends Model
{
    use SoftDeletes;
    const EXPIRY_TERM = [
        'MONTH' => 'month',
        'DAY' => 'day',
        'WEEK' => 'week',
    ];

    const EXPIRY_DATE_OPTION = [
        'CURRENT_DATE' => 'Current Date',
        'FIRST_OF_THE_MONTH' => 'First of the month',
        'LAST_DAY_OF_THE_MONTH' => 'Last day of the month',
        'PAYMENT_DATE' => 'Payment date',
        'SUBSCRIPTION_ASSIGNED' => 'Subscription Assigned',
        'JOIN_DATE' => 'Join date'
    ];

    const EXPIRY = [
        'DATE_OF_MATURITY' => 'Date of Maturity',
        'END_OF_MONTH' => 'End of Month',
    ];


    protected $appends = [
        'due_amount',
        'due',
        'over_due',
        'total',
        'member_count',
        'last_thirty_day_member_count',
        'last_sixty_day_member_count',
        'last_ninty_day_member_count'
    ];

    public static function AuthorizedFields()
    {
        return [
            'title',
            'joining_fee',
            'subscription_fee',
            'expires',
            'expiry_duration',
            'expiry_term',
            'expiry',
            'overdue',
            'overdue_duration',
            'overdue_term',
            'pro_rata',
            'amount',
            'frequency',
            'late_payment',
            'late_payment_duration',
            'late_payment_term',
            'late_fee',
            'auto_assign',
            'payment_reminder',
            'payment_reminder_term',
            'send_invoice',
            'send_invoice_date',
            'pro_rata_date',
            'overdue_fee',
            'overdue_days',
            'renewal_date_term',
            'start_date_term',
            'expiry_quantity',
            'pro_rata_birthday'
        ];
    }


    public function emailTemplates()
    {
        return $this->belongsToMany(EmailTemplate::class,'email_template_subscription','subscription_id','id');
    }

    //region Relationships
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);

    }
    //endregion

    //region Appends
    public function getDueAmountAttribute()
    {
        return 200;
    }

    /**
     * @return int
     */
    public function getDueAttribute()
    {
        return $this->members()->notResigned()->notExpired()->where('due', IStatus::ACTIVE)->count();
    }

    /**
     * @return int
     */
    public function getTotalAttribute()
    {
        return $this->members()->notResigned()->notExpired()->where('status', IStatus::ACTIVE)->count();
    }


    /**
     * @return int
     */
    public function getOverDueAttribute()
    {
        return $this->members()->whereNotNull('renewal')->notResigned()->notExpired()->whereBetween('renewal',[ Carbon::now()->subDay(29)->toDateTimeString(),Carbon::now()->toDateTimeString()])->count();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function members()
    {
        return $this->hasMany(Member::class);
    }

    /**
     * @return int
     */
    public function getLastThirtyDayMemberCountAttribute()
    {
        $memberCount = $this->members()->notResigned()->notExpired()->whereBetween('renewal',[Carbon::now()->subDays(59)->toDateTimeString(),Carbon::now()->subDays(30)->toDateTimeString()])->count();
        return $memberCount;
    }

    /**
     * @return int
     */
    public function getLastSixtyDayMemberCountAttribute()
    {
        $memberCount = $this->members()->notResigned()->notExpired()->whereBetween('renewal',[Carbon::now()->subDays(89)->toDateTimeString(),Carbon::now()->subDays(60)->toDateTimeString()])->count();
        return $memberCount;
    }

    /**
     * @return int
     */
    public function getLastNintyDayMemberCountAttribute()
    {
        return $this->members()->notResigned()->notExpired()->where('renewal', '<', Carbon::now()->subDays(90)->toDateTimeString())->count();
    }

    /**
     * @return int
     */
    public function getMemberCountAttribute()
    {
        return $this->members()->count();
    }
    //endregion
}

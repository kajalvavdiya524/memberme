<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\UserInvoice
 *
 * @property int $id
 * @property int $organization_id
 * @property int $plan_id
 * @property string|null $period_start
 * @property string|null $period_end
 * @property float|null $amount_due
 * @property float|null $amount_paid
 * @property float|null $amount_remaining
 * @property float|null $tax
 * @property float|null $total
 * @property float|null $application_fee
 * @property int|null $attempt_count
 * @property int|null $attempted
 * @property int|null $auto_advance
 * @property string|null $billing
 * @property int|null $closed
 * @property string|null $currency
 * @property string|null $customer
 * @property string|null $date
 * @property float|null $ending_balance
 * @property string|null $finalized_at
 * @property string|null $forgiven
 * @property string|null $lines
 * @property string|null $number
 * @property string|null $paid
 * @property string|null $receipt_number
 * @property string|null $status
 * @property string|null $subscription
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Organization $organization
 * @property-read \App\Plan $plan
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInvoice whereAmountDue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInvoice whereAmountPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInvoice whereAmountRemaining($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInvoice whereApplicationFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInvoice whereAttemptCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInvoice whereAttempted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInvoice whereAutoAdvance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInvoice whereBilling($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInvoice whereClosed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInvoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInvoice whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInvoice whereCustomer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInvoice whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInvoice whereEndingBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInvoice whereFinalizedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInvoice whereForgiven($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInvoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInvoice whereLines($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInvoice whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInvoice whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInvoice wherePaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInvoice wherePeriodEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInvoice wherePeriodStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInvoice wherePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInvoice whereReceiptNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInvoice whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInvoice whereSubscription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInvoice whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInvoice whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInvoice whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInvoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInvoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInvoice query()
 */
class UserInvoice extends Model
{
    /**
     * Organization Associated with current organization.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}

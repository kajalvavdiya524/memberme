<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Payment
 *
 * @property int $id
 * @property int|null $organization_id
 * @property float|null $amount
 * @property int|null $card
 * @property int|null $email
 * @property string|null $gateway
 * @property int|null $payment_status
 * @property int|null $payment_type
 * @property string|null $item_type
 * @property int|null $item_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int|null $subscription_id
 * @property float|null $discount
 * @property float|null $total
 * @property int|null $status
 * @property int|null $transaction_id
 * @property int|null $is_first_payment
 * @property float|null $sub_total
 * @property-read mixed $member
 * @property-read \App\Subscription|null $subscription
 * @property-read \App\Organization|null $organization
 * @property-read \App\Transaction|null $transaction
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment whereCard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment whereGateway($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment whereIsFirstPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment whereItemType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment wherePaymentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment whereSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment whereSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment query()
 */
class Payment extends Model
{
    protected $fillable = [
        'created_at','transaction_id', 'status'
    ];

    const GATEWAY = [
        'PAYPAL' => 'Paypal',
        'PAY_STATION' => 'Pay Station'
    ];

    /* @const ITEM_TYPE List of types who can pay */
    const ITEM_TYPE = [
        'MEMBER' => 'Member'
    ];

    const AUTHORISED_FIELDS = [
        'amount','card','email','payment_status','discount','total'
    ];

    public $appends = [
        'member','subscription'
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transaction(){
        return $this->belongsTo(Transaction::class);
    }

    public function getMemberAttribute()
    {
        if($this->item_type == Payment::ITEM_TYPE['MEMBER'] && !empty($this->item_id)){
            return Member::find($this->item_id);
        }
        return null;
    }


    public function getSubscriptionAttribute()
    {
        if($this->item_type == Payment::ITEM_TYPE['MEMBER'] && !empty($this->subscription_id)){
            return Subscription::find($this->subscription_id);
        }
        return null;
    }

}

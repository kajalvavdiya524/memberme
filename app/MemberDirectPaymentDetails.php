<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MemberDirectPaymentDetails
 *
 * @package App
 * @property $payment_amount
 * @property $payments_to_make
 * @property $payments_remaining
 * @property $next_payment_date
 * @property $owing
 * @property $id
 * @property $created_at
 * @property $updated_at
 * @property $member
 * @property int $member_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberDirectPaymentDetails whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberDirectPaymentDetails whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberDirectPaymentDetails whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberDirectPaymentDetails whereNextPaymentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberDirectPaymentDetails whereOwing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberDirectPaymentDetails wherePaymentAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberDirectPaymentDetails wherePaymentsRemaining($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberDirectPaymentDetails wherePaymentsToMake($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberDirectPaymentDetails whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberDirectPaymentDetails newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberDirectPaymentDetails newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberDirectPaymentDetails query()
 */

class MemberDirectPaymentDetails extends Model
{

    public static function AuthorizedFields()
    {
        return [
            'payment_amount',
            'payments_remaining',
            'payments_to_make',
            'next_payment_date',
            'owing',
        ];
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Receipt
 *
 * @property int $id
 * @property string|null $due_date
 * @property int|null $organization_id
 * @property int|null $receipt_no
 * @property int|null $payer_member_id
 * @property float|null $sub_total
 * @property float|null $gst
 * @property float|null $total
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int|null $send_email
 * @property string|null $payment_date
 * @property-read \App\Organization|null $organization
 * @property-read \App\Member|null $payer
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Receipt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Receipt whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Receipt whereGst($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Receipt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Receipt whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Receipt wherePayerMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Receipt wherePaymentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Receipt whereReceiptNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Receipt whereSendEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Receipt whereSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Receipt whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Receipt whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Receipt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Receipt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Receipt query()
 */
class Receipt extends Model
{
    const DEFAULT_GST = 15;

    /**
     * Will return member.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payer(){
        return $this->belongsTo(Member::class,'payer_member_id','id');
    }


    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}

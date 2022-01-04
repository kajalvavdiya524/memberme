<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Transaction
 *
 * @property int $id
 * @property string|null $payment_type
 * @property int|null $status
 * @property int|null $organization_id
 * @property int|null $receipt_id
 * @property int|null $payer_member_id
 * @property int|null $total
 * @property string|null $expiry_date_time
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read mixed $receipt_no
 * @property-read \App\Organization|null $organization
 * @property-read \App\OrganizationDetail|null $organizationDetails
 * @property-read \App\Member|null $payer
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Payment[] $payments
 * @property-read \App\Receipt|null $receipt
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereExpiryDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction wherePayerMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction wherePaymentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereReceiptId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction query()
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\TransactionPartpay[] $partPays
 * @property float $balance_owing
 * @property float $paid
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereBalanceOwing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction wherePaid($value)
 * @property-read int|null $part_pays_count
 * @property-read int|null $payments_count
 */
class Transaction extends Model
{
    protected  $appends = [
      'receipt_no',
    ];
    const TRANSACTION_TYPE = [
        'CASH' => 'cash',
        'EFTPOS' => 'eftpos',
        'OTHER' => 'other'
    ];

    //================================= Relationships

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }

    public function payer(){
        return $this->belongsTo(Member::class,'payer_member_id','id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function organizationDetails(){
        return $this->belongsTo(OrganizationDetail::class,'organization_id','organization_id');
    }

    public function partPays()
    {
        return $this->hasMany(TransactionPartpay::class);
    }

    //================================ Appends
    public function getReceiptNoAttribute()
    {
        if(isset($this->receipt)){
            return $this->receipt->receipt_no;
        }
        return null;
    }
}

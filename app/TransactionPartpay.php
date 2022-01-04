<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\TransactionPartpay
 *
 * @property int $id
 * @property int|null $payment_type_id
 * @property int|null $transaction_id
 * @property float|null $amount
 * @property float|null $owing_amount
 * @property float|null $status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\PaymentType|null $PaymentType
 * @property-read \App\Transaction|null $transaction
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransactionPartpay whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransactionPartpay whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransactionPartpay whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransactionPartpay whereOwingAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransactionPartpay wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransactionPartpay whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransactionPartpay whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransactionPartpay whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPartpay newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPartpay newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPartpay query()
 */
class TransactionPartpay extends Model
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function PaymentType()
    {
        return $this->belongsTo(PaymentType::class);
    }

}

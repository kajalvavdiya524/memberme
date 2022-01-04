<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\VoucherLog
 *
 * @property int $id
 * @property int $voucher_id
 * @property float|null $redeemed_amount
 * @property float|null $balance
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property mixed $organization
 * @property-read \App\Voucher $voucher
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherLog whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherLog whereRedeemedAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherLog whereVoucherId($value)
 * @mixin \Eloquent
 * @property int|null $organization_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherLog whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherLog query()
 */
class VoucherLog extends Model
{
    /**
     * Related voucher
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}

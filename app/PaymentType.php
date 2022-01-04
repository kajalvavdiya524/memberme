<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\PaymentType
 *
 * @property int $id
 * @property int $organization_id
 * @property string|null $name
 * @property int $status
 * @property-read \App\Organization $organization
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentType whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentType whereStatus($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentType query()
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\TransactionPartpay[] $partPays
 * @property-read int|null $part_pays_count
 */
class PaymentType extends Model
{
    const TYPE = [
        'CASH' => 'Cash',
    ];
    public  $timestamps = false;

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function partPays()
    {
        return $this->hasMany(TransactionPartpay::class);
    }
}

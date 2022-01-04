<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\VoucherParameter
 *
 * @property int $id
 * @property int|null $voucher_type
 * @property string|null $promo_id
 * @property string|null $voucher_name
 * @property int|null $multisite
 * @property int|null $multisite_organizations
 * @property string|null $voucher_code
 * @property int|null $expires
 * @property string|null $expiry
 * @property string|null $expiry_period
 * @property string|null $expiry_date
 * @property int|null $uses
 * @property int|null $limited_quantity
 * @property int|null $availability
 * @property int|null $min_value
 * @property int|null $max_value
 * @property int|null $value
 * @property int|null $value_mode
 * @property string|null $voucher_image
 * @property string|null $voucher_back
 * @property int|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Voucher[] $voucher
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherParameter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherParameter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherParameter query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherParameter whereAvailability($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherParameter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherParameter whereExpires($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherParameter whereExpiry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherParameter whereExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherParameter whereExpiryPeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherParameter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherParameter whereLimitedQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherParameter whereMaxValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherParameter whereMinValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherParameter whereMultisite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherParameter whereMultisiteOrganizations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherParameter wherePromoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherParameter whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherParameter whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherParameter whereUses($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherParameter whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherParameter whereValueMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherParameter whereVoucherBack($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherParameter whereVoucherCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherParameter whereVoucherImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherParameter whereVoucherName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherParameter whereVoucherType($value)
 * @mixin \Eloquent
 * @property string|null $expiry_period_quantity
 * @property string|null $expiry_period_duration
 * @property string|null $voucher_front_image
 * @property string|null $voucher_back_image
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Organization[] $organizations
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherParameter whereExpiryPeriodDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherParameter whereExpiryPeriodQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherParameter whereVoucherBackImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherParameter whereVoucherFrontImage($value)
 * @property float|null $value_quantity
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherParameter whereValueQuantity($value)
 * @property string|null $front_image_style
 * @property string|null $back_image_style
 * @property int|null $limited
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherParameter whereBackImageStyle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherParameter whereFrontImageStyle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherParameter whereLimited($value)
 * @property string|null $expiry_mode
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoucherParameter whereExpiryMode($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Voucher[] $vouchers
 * @property-read int $sold_count
 * @property-read int|null $organizations_count
 * @property-read int|null $vouchers_count
 */
class VoucherParameter extends Model
{

    const VOUCHER_TYPE = [
        'Gift' => 1,
        'Birthday' => 2,
        'Ticket' => 3,
    ];
    const EXPIRES = [
        'Yes' => 1,
        'No' => 2,
    ];
    const EXPIRY_MODE = [
        'Period' => 'period',
        'Date' => 'date',
    ];
    const LIMITED_QUANTITY = [
        'Yes' => 1,
        'No' => 2,
    ];
    const VALUE = [
        'Set_Value' => 1,
        'Variable' => 2,
    ];
    const VALUE_MODE = [
        '$' => 1,
        '%' => 2,
    ];
    const EXPIRY_DURATION = [
        'Day' => 'day',
        'Week' => 'week',
        'Month' => 'month',
        'Year' => 'year',
    ];
    const EXPIRY_QUANTITY = [
        'One' => '1',
        'Two' => '2',
        'Three' => '3',
        'Four' => '4',
        'Five' => '5',
        'Six' => '6',
        'Seven' => '7',
        'Eight' => '8',
        'Nine' => '9',
        ];
    const USES = [
        'Unlimited' => -1,
        '1' => 1,
        '2' => 2,
        '3' => 3,
        '4' => 4,
        '5' => 5,
        '6' => 6,
        '7' => 7,
        '8' => 8,
        '9' => 9,
        ];
    const VOUCHER_STATUS = [
        'Valid' => 1,
        'Validated' => 2,
        'Expired' => 3,

    ];

    protected $appends = [
        'sold_count'
    ];

    //
    public function vouchers(){
        return $this->hasMany(Voucher::class);

    }

    public function organizations(){
        return $this->belongsToMany(Organization::class,'organization_voucher_parameter','voucher_parameter_id','id');
    }

    /**
     * @return int
     */
    public function getSoldCountAttribute()
    {
        return $this->vouchers()->count();
    }
}

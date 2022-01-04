<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\KioskVoucherParameter
 *
 * @property int $id
 * @property int $voucher_parameter_id
 * @property int|null $frequency
 * @property int|null $duration
 * @property int|null $days_before
 * @property int|null $days_after
 * @property int $kiosk_print
 * @property int $email_voucher
 * @property int $show_in_app
 * @property string|null $display_message
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Kiosk $kiosk
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskVoucherParameter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskVoucherParameter whereDaysAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskVoucherParameter whereDaysBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskVoucherParameter whereDisplayMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskVoucherParameter whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskVoucherParameter whereEmailVoucher($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskVoucherParameter whereFrequency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskVoucherParameter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskVoucherParameter whereKioskPrint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskVoucherParameter whereShowInApp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskVoucherParameter whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskVoucherParameter whereVoucherParameterId($value)
 * @mixin \Eloquent
 * @property string|null $sound
 * @property mixed $voucher_parameter
 * @property mixed $organization
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskVoucherParameter whereSound($value)
 * @property int|null $organization_id
 * @property int|null $status
 * @property string|null $voucher_message
 * @property-read \App\VoucherParameter $voucherParameter
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskVoucherParameter whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskVoucherParameter whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskVoucherParameter whereVoucherMessage($value)
 * @property int|null $lighting
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskVoucherParameter whereLighting($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskVoucherParameter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskVoucherParameter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskVoucherParameter query()
 */
class KioskVoucherParameter extends Model
{

    const FREQUENCY = [
        'ONCE_ONLY' => 101,
        'ONCE_A_DAY' => 102,
        'ONCE_A_WEEK' => 103,
        'ONCE_A_MONTH' => 104,
    ];

    const DURATION = [
        'DAY_OF_BIRTHDAY' => 201,
        'WEEK_OF_BIRTHDAY' => 202,
        'MONTH_OF_BIRTHDAY' => 203,
        'DAYS_EITHER_SIDE' => 204,
    ];

//    /**
//     * kiosk for these settings.
//     * @return \Illuminate\Database\Eloquent\Relations\HasOne
//     */
//    public function kiosk()
//    {
//        return $this->hasOne(Kiosk::class);
//    }

    /**
     * Voucher Parameter for that.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function voucherParameter()
    {
        return $this->belongsTo(VoucherParameter::class);
    }

    /**
     * Organization related to that kiosk_voucher_parameter_settings.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}

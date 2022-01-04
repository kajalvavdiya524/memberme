<?php

namespace App;

use App\base\IStatus;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Kiosk
 *
 * @package App
 * @property $id
 * @property $name
 * @property $organization_id
 * @property $status
 * @property $mac
 * @property $created_at
 * @property $updated_at
 * @property int|null $background_id
 * @property-read \App\Organization|null $organization
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereBackgroundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereMac($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk query()
 * @property int|null $advertising_id
 * @property mixed $voucher_parameter
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereAdvertisingId($value)
 * @property-read \App\Advertising|null $advertising
 * @property-read \App\KioskBackground|null $background
 * @property-read \App\KioskVoucherParameter $voucherParameter
 * @property int|null $kiosk_voucher_parameter_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Kiosk whereKioskVoucherParameterId($value)
 */
class Kiosk extends Model
{
    const STATUS = [
        'ASSIGNED'    => 'Assigned',
        'PENDING'  => 'Pending',
        'DISABLED'  => 'Disabled',
    ];

    protected $hidden = [
        'mac'
    ];

    protected $fillable = [
        'mac'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function background()
    {
        return $this->belongsTo(KioskBackground::class,'background_id','id');
    }

    public function advertising()
    {
        return $this->belongsTo(Advertising::class,'advertising_id','id');
    }

    /**
     * Kiosk voucher parameter and the settings against that.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function voucherParameter()
    {
        return $this->belongsTo(KioskVoucherParameter::class,'kiosk_voucher_parameter_id','id');
    }

}

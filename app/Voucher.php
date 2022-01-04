<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Voucher
 *
 * @property int $id
 * @property int $voucher_parameter_id
 * @property string|null $voucher_code
 * @property int|null $status
 * @property \App\Organization $organization
 * @property string|null $voucher_name
 * @property string|null $customer_name
 * @property string|null $customer_email
 * @property string|null $purchase_date
 * @property string|null $voucher_value
 * @property int|null $voucher_balance
 * @property string|null $expiry_date
 * @property int|null $allowed_validations
 * @property int|null $validations_made
 * @property string|null $last_validations
 * @property int|null $validations_left
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\VoucherParameter $voucherParameter
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Voucher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Voucher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Voucher query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Voucher whereAllowedValidations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Voucher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Voucher whereCustomerEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Voucher whereCustomerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Voucher whereExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Voucher whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Voucher whereLastValidations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Voucher whereOrganization($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Voucher wherePurchaseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Voucher whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Voucher whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Voucher whereValidationsLeft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Voucher whereValidationsMade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Voucher whereVoucherBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Voucher whereVoucherCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Voucher whereVoucherName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Voucher whereVoucherParameterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Voucher whereVoucherValue($value)
 * @mixin \Eloquent
 * @property int $organization_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Voucher whereOrganizationId($value)
 * @property float|null $value_mode
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Voucher whereValueMode($value)
 * @property string|null $back_image
 * @property string|null $front_image
 * @property mixed $voucher_parameter
 * @property mixed $logs
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Voucher whereBackImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Voucher whereFrontImage($value)
 * @property string|null $qr_code
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Voucher whereQrCode($value)
 * @property-read int|null $logs_count
 */
class Voucher extends Model
{
    //
    const VOUCHER_STATUS = [
        'Valid' => 1,
        'Validated' => 2,
        'Expired' => 3,
    ];
    public function voucherParameter(){
        return $this->belongsTo(VoucherParameter::class);
    } //
    public function organization(){
        return $this->belongsTo(Organization::class);
    }

    public function logs()
    {
        return $this->hasMany(VoucherLog::class);
    }

    public function setVoucherCode(){
        $vouchercode = 1;
        return $this;
    }

    public function addVoucherLog($amountToRedeem = 0)
    {
        $voucherLog = new VoucherLog();
        $voucherLog->redeemed_amount = $amountToRedeem;
        $voucherLog->balance = $this->voucher_balance;
        $voucherLog->voucher_id = $this->id;
        $voucherLog->organization_id = $this->organization_id;
        $voucherLog->save();
    }
}

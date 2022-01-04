<?php

namespace App;

use App\Traits\DisableLazyLoad;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OrganizationSmsSetting
 *
 * @package App
 * @property $url
 * @property $account_id
 * @property $sms_username
 * @property $sms_password
 * @property $organization_id
 * @property $sms_rate
 * @property $sms_balance
 * @property $type
 * @property $status
 * @property $api_key
 * @property $api_secret
 * @property int $id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $country_code
 * @property-read \App\Organization $organization
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationSmsSetting whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationSmsSetting whereApiKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationSmsSetting whereApiSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationSmsSetting whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationSmsSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationSmsSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationSmsSetting whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationSmsSetting whereSmsBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationSmsSetting whereSmsPassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationSmsSetting whereSmsRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationSmsSetting whereSmsUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationSmsSetting whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationSmsSetting whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationSmsSetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationSmsSetting whereUrl($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationSmsSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationSmsSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationSmsSetting query()
 */
class OrganizationSmsSetting extends Model
{
    use DisableLazyLoad;

    const AUTHORISED_FIELDS = [
        'api_key','api_secret', 'sms_username','sms_password', 'sms_rate', 'url','account_id','country_code'
    ];

    //region Relations
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
    //endregion

    public function getSmsBalanceAttribute($value)
    {
        try {
            $floatBalance = (float)$value;
            $floatBalance = number_format($floatBalance, 2);
            return $floatBalance;
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
        }
    }
}

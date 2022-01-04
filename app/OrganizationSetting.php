<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\OrganizationSetting
 *
 * @property int $id
 * @property int $organization_id
 * @property string|null $subscription_start_date
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Organization $organization
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationSetting whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationSetting whereSubscriptionStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationSetting whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationSetting query()
 */
class OrganizationSetting extends Model
{

    const SUBSCRIPTION_DROPDOWN_OPTION = [
        'PAYMENT_DATE' => 'Payment Date',
        'DATE_SUBSCRIPTION_ASSIGNED' => 'Date Subscription Assigned',
        'DATE_MEMBER_CREATED' => 'Date Member Created',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
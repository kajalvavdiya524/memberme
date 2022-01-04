<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\StripeSubscription
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $organization_id
 * @property int $plan_id
 * @property string|null $current_period_end
 * @property string|null $current_period_start
 * @property string|null $customer
 * @property int|null $days_until_due
 * @property string|null $default_source
 * @property float|null $discount
 * @property string|null $ended_at
 * @property int|null $has_more
 * @property int|null $total_count
 * @property int|null $quantity
 * @property string|null $start
 * @property string|null $status
 * @property string|null $tax_percent
 * @property string|null $ref_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StripeSubscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StripeSubscription whereCurrentPeriodEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StripeSubscription whereCurrentPeriodStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StripeSubscription whereCustomer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StripeSubscription whereDaysUntilDue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StripeSubscription whereDefaultSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StripeSubscription whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StripeSubscription whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StripeSubscription whereHasMore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StripeSubscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StripeSubscription whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StripeSubscription wherePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StripeSubscription whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StripeSubscription whereStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StripeSubscription whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StripeSubscription whereTaxPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StripeSubscription whereTotalCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StripeSubscription whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StripeSubscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StripeSubscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StripeSubscription query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StripeSubscription whereRefId($value)
 */
class StripeSubscription extends Model
{
    //
}

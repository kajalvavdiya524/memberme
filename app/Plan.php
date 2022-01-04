<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Plan
 *
 * @package App
 * @property $features
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property int $id
 * @property string $ref_id
 * @property string $tiers
 * @property string $product
 * @property string $trial_period_days
 * @property string $nickname
 * @property string $name
 * @property float $amount
 * @property int $interval_count
 * @property string|null $interval
 * @property string|null $currency
 * @property string|null $billing_scheme
 * @property string|null $metadata
 * @property string|null $tiers_mode
 * @property string|null $transform_usage
 * @property int $status
 * @property float|null $yearly_discount
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan whereBillingScheme($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan whereInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan whereIntervalCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan whereProduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan whereTiers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan whereTiersMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan whereTransformUsage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan whereTrialPeriodDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan whereYearlyDiscount($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan whereRefId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Plan query()
 * @property-read int|null $features_count
 */
class Plan extends Model
{
    const DURATION_TYPE = [
        'DAY' => 'day',
        'WEEK' => 'week',
        'MONTH' => 'month',
        'YEAR' => 'year',
        'LIFE_TIME' => 'life time',
    ];

    const TRAIL = 1;

    public function features()
    {
        return $this
            ->belongsToMany(Feature::class)
            ->as('featurePlanPivot')
            ->withTimestamps()
            ->withPivot(['limit','amount']);
    }

    public function setMetadataAttribute($value)
    {
        $this->attributes['metadata'] = serialize_data($value);
    }

    public function getMetadataAttribute()
    {
        if(empty($this->attributes['metadata'])){
            return null;
        }
        return un_serialize_data($this->attributes['metadata']);
    }

}

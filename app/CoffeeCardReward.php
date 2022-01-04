<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\CoffeeCardReward
 *
 * @property int $id
 * @property int $coffee_card_id
 * @property int|null $expiry
 * @property string|null $expiry_date
 * @property string|null $qr_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property mixed $coffee_card
 * @property-read \App\CoffeeCard $coffeeCard
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CoffeeCardReward newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CoffeeCardReward newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CoffeeCardReward query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CoffeeCardReward whereCoffeeCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CoffeeCardReward whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CoffeeCardReward whereExpiry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CoffeeCardReward whereExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CoffeeCardReward whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CoffeeCardReward whereQrCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CoffeeCardReward whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CoffeeCardReward whereName($value)
 * @property string|null $message
 * @property string|null $image
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CoffeeCardReward whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CoffeeCardReward whereMessage($value)
 * @property string|null $expiry_mode
 * @property int|null $expiry_period_quantity
 * @property string|null $expiry_period_duration
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CoffeeCardReward whereExpiryMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CoffeeCardReward whereExpiryPeriodDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CoffeeCardReward whereExpiryPeriodQuantity($value)
 * @property string|null $reward_code
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CoffeeCardReward whereRewardCode($value)
 */
class CoffeeCardReward extends Model
{
    const EXPIRY_MODE = [
        'PERIOD' => 'Period',
        'DATE' => 'Date'
    ];

    const EXPIRY = [
        'YES' => 1,
        'NO' => 2
    ];

    const EXPIRY_DURATION = [
        'DAY' => 'Day',
        'WEEK' => 'Week',
        'MONTH' => 'Month',
        'YEAR' => 'Year'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function coffeeCard()
    {
        return $this->belongsTo(CoffeeCard::class);
    }
}

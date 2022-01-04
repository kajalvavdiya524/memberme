<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\MemberCoffeeCard
 *
 * @property int $id
 * @property int $coffee_card_id
 * @property int $member_id
 * @property string|null $code
 * @property string|null $qr_code
 * @property int|null $stamp_balance
 * @property int|null $stamp_earned
 * @property int|null $stamp_required
 * @property int|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property mixed $coffee_card
 * @property-read \App\CoffeeCard $coffeeCard
 * @property-read \App\Member $member
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCard query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCard whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCard whereCoffeeCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCard whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCard whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCard whereQrCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCard whereStampBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCard whereStampEarned($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCard whereStampRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCard whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCard whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $coffee_card_image
 * @property mixed $rewards
 * @property mixed $member_coffee_card_log
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCard whereCoffeeCardImage($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\MemberCoffeeCardLog[] $memberCoffeeCardLog
 * @property-read int|null $member_coffee_card_log_count
 */
class MemberCoffeeCard extends Model
{
    const STATUS = [
        'ACTIVE' => 1,
        'INACTIVE' => 2,
        'REWARD_EARNED' => 3,
    ];

    const CODE_LENGTH = 12;
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function coffeeCard()
    {
        return $this->belongsTo(CoffeeCard::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rewards()
    {
        return $this->belongsTo(MemberCoffeeCardReward::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function memberCoffeeCardLog()
    {
        return $this->hasMany(MemberCoffeeCardLog::class);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\MemberCoffeeCardReward
 *
 * @property int $id
 * @property string $name
 * @property int|null $member_id
 * @property int|null $coffee_card_reward_id
 * @property string|null $reward_entry_date
 * @property int|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCardReward newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCardReward newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCardReward query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCardReward whereCoffeeCardRewardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCardReward whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCardReward whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCardReward whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCardReward whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCardReward whereRewardEntryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCardReward whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCardReward whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $code
 * @property string|null $qr_code
 * @property string|null $member_coffee_card_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCardReward whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCardReward whereQrCode($value)
 * @property string|null $reward_expiry_date
 * @property mixed $member
 * @property mixed $coffee_card_reward
 * @property mixed $member_coffee_card
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCardReward whereRewardExpiryDate($value)
 * @property string|null $redeem_date_time
 * @property-read \App\CoffeeCardReward|null $coffeeCardReward
 * @property-read \App\MemberCoffeeCard|null $memberCoffeeCard
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCardReward whereMemberCoffeeCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCardReward whereRedeemDateTime($value)
 * @property int|null $earned_at
 * @property int|null $redeemed_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCardReward whereEarnedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCardReward whereRedeemedAt($value)
 */
class MemberCoffeeCardReward extends Model
{

    const STATUS = [
        'ACTIVE' => 1,
        'EXPIRED' => 3,
        'REDEEMED' => 4,
    ];

    const CODE_LENGTH = 10;
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
    public function coffeeCardReward()
    {
        return $this->belongsTo(CoffeeCardReward::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function memberCoffeeCard()
    {
        return $this->belongsTo(MemberCoffeeCard::class);
    }
}

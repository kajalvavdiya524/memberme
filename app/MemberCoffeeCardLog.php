<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\MemberCoffeeCardLog
 *
 * @property int $id
 * @property int $stamp_added
 * @property int|null $member_id
 * @property int|null $member_coffee_card_id
 * @property int|null $organization_id
 * @property string $stamp_added_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property mixed $organization
 * @property mixed $member_coffee_card
 * @property mixed $member
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCardLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCardLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCardLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCardLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCardLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCardLog whereMemberCoffeeCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCardLog whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCardLog whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCardLog whereStampAdded($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCardLog whereStampAddedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCardLog whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\MemberCoffeeCard|null $memberCoffeeCard
 * @property int|null $earned_at
 * @property int|null $redeemed_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCardLog whereEarnedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberCoffeeCardLog whereRedeemedAt($value)
 */
class MemberCoffeeCardLog extends Model
{
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
    public function memberCoffeeCard()
    {
        return $this->belongsTo(MemberCoffeeCard::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}

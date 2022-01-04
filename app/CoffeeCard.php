<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\CoffeeCard
 *
 * @property int $id
 * @property int $organization_id
 * @property string|null $background
 * @property int|null $number_of_stemps
 * @property string|null $position
 * @property string|null $style
 * @property string|null $card_code
 * @property string|null $qr_code
 * @property int|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property mixed $member_coffee_cards
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\MemberCoffeeCard[] $memberCoffeeCards
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CoffeeCardReward[] $rewards
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CoffeeCard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CoffeeCard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CoffeeCard query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CoffeeCard whereBackground($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CoffeeCard whereCardCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CoffeeCard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CoffeeCard whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CoffeeCard whereNumberOfStemps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CoffeeCard whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CoffeeCard wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CoffeeCard whereQrCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CoffeeCard whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CoffeeCard whereStyle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CoffeeCard whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $card_name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CoffeeCard whereCardName($value)
 * @property int|null $number_of_stamps
 * @property mixed $member_coffee_card_rewards
 * @property mixed $member_coffee_card_log
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Organization[] $organizations
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CoffeeCard whereNumberOfStamps($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\MemberCoffeeCardReward[] $memberCoffeeCardRewards
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\MemberCoffeeCardLog[] $memberCoffeeCardLog
 * @property-read int|null $member_coffee_card_log_count
 * @property-read int|null $member_coffee_card_rewards_count
 * @property-read int|null $member_coffee_cards_count
 * @property-read int|null $organizations_count
 * @property-read int|null $rewards_count
 */
class CoffeeCard extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function organizations()
    {
        return $this->belongsToMany(Organization::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function memberCoffeeCards()
    {
        return $this->hasMany(MemberCoffeeCard::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function memberCoffeeCardRewards()
    {
        return $this->hasManyThrough(MemberCoffeeCardReward::class,MemberCoffeeCard::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rewards()
    {
        return $this->hasMany(CoffeeCardReward::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function memberCoffeeCardLog()
    {
        return $this->hasManyThrough(MemberCoffeeCardLog::class,MemberCoffeeCard::class);
    }
    /* ========================================== Mutators =====================================================*/
    /**
     * @param $value
     */
    public function setStyleAttribute($value)
    {
        $this->attributes['style'] = serialize_data($value);
    }

    /**
     * @return mixed
     */
    public function getStyleAttribute()
    {
        if(!empty($this->attributes['style']))
            return un_serialize_data($this->attributes['style']);
        return null;
    }
}

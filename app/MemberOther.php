<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MemberOther
 *
 * @package App
 * @property $created_at
 * @property $updated_at
 * @property $prox_card
 * @property $swipe_card
 * @property $price_level
 * @property $points
 * @property $senior
 * @property $deceased
 * @property $approved
 * @property $earn_points
 * @property $mailing_list
 * @property $newsletter
 * @property $receive_sms
 * @property $receive_email
 * @property $recieve_sms
 * @property $secondary_member_id
 * @property $proposer_member_id
 * @property $parent_code
 * @property $occupation
 * @property $transferred_from
 * @property $company
 * @property $rsa_type
 * @property $discount
 * @property $credit_limit
 * @property $prox_card_suffix
 * @property $swipe_card_suffix
 * @property $prox_card_prefix
 * @property $swipe_card_prefix
 * @property $rsa
 * @property $served
 * @property $member_id
 * @property int $id
 * @property-read mixed $parent_name
 * @property-read mixed $proposer_name
 * @property-read mixed $secondary_name
 * @property-read \App\Member $member
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberOther whereApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberOther whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberOther whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberOther whereCreditLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberOther whereDeceased($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberOther whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberOther whereEarnPoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberOther whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberOther whereMailingList($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberOther whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberOther whereNewsletter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberOther whereOccupation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberOther whereParentCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberOther wherePoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberOther wherePriceLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberOther whereProposerMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberOther whereProxCard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberOther whereProxCardPrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberOther whereProxCardSuffix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberOther whereReceiveEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberOther whereReceiveSms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberOther whereRsa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberOther whereRsaType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberOther whereSecondaryMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberOther whereSenior($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberOther whereServed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberOther whereSwipeCard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberOther whereSwipeCardPrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberOther whereSwipeCardSuffix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberOther whereTransferredFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberOther whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberOther newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberOther newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberOther query()
 * @property int|null $physical_card
 * @property int|null $print_card
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberOther wherePhysicalCard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberOther wherePrintCard($value)
 */
class MemberOther extends Model
{
    protected $fillable = [
        'member_id',
    ];


    protected $appends = [
        'secondary_name', 'proposer_name', 'parent_name'
    ];

    public static function AuthorizedFields()
    {
        return [
            'receive_email', 'receive_sms', 'newsletter', 'mailing_list', 'earn_points', 'approved', 'rsa', 'deceased', 'price_level', 'senior', 'points', 'swipe_card', 'prox_card', 'swipe_card_prefix', 'swipe_card_suffix', 'prox_card_prefix', 'prox_card_suffix', 'credit_limit', 'discount', 'rsa_type', 'company', 'transferred_from', 'occupation', 'parent_code', 'proposer_member_id', 'secondary_member_id','physical_card','print_card','served'
        ];
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function getSecondaryNameAttribute()
    {
        if (isset($this->secondary_member_id)) {
            $member = Member::whereId($this->member_id)->select('id','organization_id')->first();
            $organization = $member->organization;
            if (!empty($organization)) {
                $member = $organization->members()->where(['member_id' => $this->secondary_member_id])->first();
                if ($member) {
                    return $member->first_name . ' ' . $member->last_name;
                }
            }
        }
        return null;
    }

    public function getProposerNameAttribute()
    {
        if (isset($this->proposer_member_id)) {
            $member = Member::whereId($this->member_id)->select('id','organization_id')->first();
            $organization = $member->organization;
            if (!empty($organization)) {
                $member = $organization->members()->where(['member_id' => $this->proposer_member_id])->first();
                if ($member) {
                    return $member->first_name . ' ' . $member->last_name;
                }
            }
        }
        return null;
    }

    public function getParentNameAttribute()
    {
        if (isset($this->parent_code)) {
            $organization = $this->member->organization;
            if (!empty($organization)) {
                $member = $organization->members()->where(['member_id' => $this->parent_code])->first();
                if ($member) {
                    return $this->parent_code . '-' . $member->first_name . ' ' . $member->last_name;
                }
            }
        }
        return null;
    }

}

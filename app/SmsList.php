<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * Class SmsList
 *
 * @package App
 * @property String $name
 * @property $ref_id
 * @property $group_id
 * @property $organization_id
 * @property $data
 * @property int $id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Group|null $group
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ListSentSms[] $sentSms
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SmsListMember[] $smsListMember
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SmsList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SmsList whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SmsList whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SmsList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SmsList whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SmsList whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SmsList whereRefId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SmsList whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SmsList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SmsList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SmsList query()
 * @property-read int|null $sent_sms_count
 * @property-read int|null $sms_list_member_count
 */
class SmsList extends Model
{
    protected $fillable = [

    ];

    /**
     * Return the group having this list
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function smsListMember()
    {
        return $this->hasMany(SmsListMember::class);
    }

    public function sentSms()
    {
        return $this->hasMany(ListSentSms::class);
    }
}

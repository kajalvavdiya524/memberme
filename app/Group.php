<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Group
 *
 * @property int $id
 * @property int|null $organization_id
 * @property string|null $name
 * @property int|null $status
 * @property string|null $type
 * @property string|null $data
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int|null $member_id
 * @property int $is_status_group
 * @property mixed $email_templates
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Member[] $members
 * @property-read \App\Organization|null $organization
 * @property-read \App\SmsList $smsList
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SmsListMember[] $smsListMember
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group whereIsStatusGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group query()
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\EmailTemplate[] $emailTemplates
 * @property-read int|null $email_templates_count
 * @property-read int|null $members_count
 * @property-read int|null $sms_list_member_count
 */
class Group extends Model
{
    const TYPE = [
        'ADJUNCT' => 'adjunct',
        'ACTIVITY' => 'activity',
        'INTEREST' => 'interest',
        'SKILL' => 'skill',
        'STATUS'  => 'status'
    ];
    protected $casts = [ 'id' => 'integer' ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function members()
    {
        return $this->belongsToMany(Member::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function smsList()
    {
        return $this->hasOne(SmsList::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function emailTemplates()
    {
        return $this->belongsToMany(EmailTemplate::class,'email_template_group','group_id','id');
    }

    public function smsListMember()
    {
        return $this->hasMany(SmsListMember::class);
    }
}
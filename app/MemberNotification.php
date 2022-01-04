<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\MemberNotification
 *
 * @property int $id
 * @property string|null $changed_fields
 * @property int|null $added_by_id
 * @property string|null $added_by_type
 * @property string|null $seen_date_time
 * @property int|null $organization_id
 * @property string|null $seen_by_user_ids
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberNotification whereAddedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberNotification whereAddedByType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberNotification whereChangedFields($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberNotification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberNotification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberNotification whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberNotification whereSeenByUserIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberNotification whereSeenDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberNotification whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $clicked_date_time
 * @property string|null $clicked_by_user_ids
 * @property int $member_id
 * @property int|null $status
 * @property-read \App\Member $member
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberNotification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberNotification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberNotification query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberNotification whereClickedByUserIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberNotification whereClickedDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberNotification whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberNotification whereStatus($value)
 * @property int|null $is_updated
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberNotification whereIsUpdated($value)
 * @property string|null $updated_date_time
 * @property int|null $updated_by_user_id
 * @property int|null $clicked_by_user_id
 * @property-read \App\User|null $clickedByUser
 * @property-read \App\User|null $updatedByUser
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberNotification whereClickedByUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberNotification whereUpdatedByUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberNotification whereUpdatedDateTime($value)
 */
class MemberNotification extends Model
{
    //
    const TYPE = [
        "MEMBER_ID" => "Member_id",
        "MEMBER" => "Member",
        "USER" => "User",
        "STATUS" => "1",
    ];

    protected $fillable = [
        'seen_date_time'
    ];


    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function clickedByUser()
    {
        return $this->belongsTo(User::class,'clicked_by_user_id','id');
    }

    public function updatedByUser()
    {
        return $this->belongsTo(User::class,'updated_by_user_id','id');
    }

    public function getChangedFieldsAttribute()
    {
        if(isset($this->attributes['changed_fields']) && !empty($this->attributes['changed_fields'])){
            return json_decode(unserialize($this->attributes['changed_fields']));
        }else{
            return null;
        }
    }

    public function setChangedFieldsAttribute($value)
    {
        if($value != null && is_array($value)){
            $this->attributes['changed_fields'] = serialize(json_encode($value));
        }
    }

}

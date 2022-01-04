<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Note
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $description
 * @property int $member_id
 * @property int $user_id
 * @property int $organization_id
 * @property int $status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read mixed $user_name
 * @property-read \App\Member $member
 * @property-read \App\Organization $organization
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Note whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Note whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Note whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Note whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Note whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Note whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Note whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Note whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Note whereUserId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Note newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Note newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Note query()
 */
class Note extends Model
{
    protected $appends = [
        'user_name'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }


    public function getUserNameAttribute()
    {
        if (isset($this->user_id)) {
            $user = $this->user;
            return $user->first_name. ' '. $user->last_name;
        }
        return null;
    }
}

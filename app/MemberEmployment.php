<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MemberEmployment
 *
 * @package App
 * @property $id
 * @property $member_id
 * @property $created_at
 * @property $date_to
 * @property $date_from
 * @property $employer
 * @property $role
 * @property $status
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Member $member
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberEmployment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberEmployment whereDateFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberEmployment whereDateTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberEmployment whereEmployer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberEmployment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberEmployment whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberEmployment whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberEmployment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberEmployment whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberEmployment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberEmployment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberEmployment query()
 */
class MemberEmployment extends Model
{
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}

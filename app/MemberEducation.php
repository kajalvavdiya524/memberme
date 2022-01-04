<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MemberEducation
 *
 * @package App
 * @property $id
 * @property $member_id
 * @property $created_at
 * @property $date_to
 * @property $date_from
 * @property $employer
 * @property $institution
 * @property $qualification
 * @property int $status
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Member $member
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberEducation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberEducation whereDateFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberEducation whereDateTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberEducation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberEducation whereInstitution($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberEducation whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberEducation whereQualification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberEducation whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberEducation whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberEducation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberEducation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberEducation query()
 */
class MemberEducation extends Model
{
    protected $table = 'member_educations';

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}

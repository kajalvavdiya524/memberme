<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\MemberViewLog
 *
 * @property int $id
 * @property string $view_time
 * @property int $member_id
 * @property int $organization_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property Organization $organization
 * @property Member $member
 * @property User $user
 * @method static \Illuminate\Database\Eloquent\Builder|MemberViewLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MemberViewLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MemberViewLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|MemberViewLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberViewLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberViewLog whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberViewLog whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberViewLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberViewLog whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberViewLog whereViewTime($value)
 * @mixin \Eloquent
 */
class MemberViewLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id', 'user_id','member_id','view_time'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

}

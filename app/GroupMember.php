<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\GroupMember
 *
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GroupMember newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GroupMember newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GroupMember query()
 * @property int $id
 * @property int $member_id
 * @property int $group_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GroupMember whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GroupMember whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GroupMember whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GroupMember whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GroupMember whereUpdatedAt($value)
 */
class GroupMember extends Model
{
    protected $table = 'group_member';
}

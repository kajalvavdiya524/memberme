<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\UserLastLogin
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $organization_id
 * @property string $last_login
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserLastLogin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserLastLogin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserLastLogin whereLastLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserLastLogin whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserLastLogin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserLastLogin whereUserId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|UserLastLogin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserLastLogin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserLastLogin query()
 */
class UserLastLogin extends Model
{
    //
}

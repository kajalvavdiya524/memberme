<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * App\VerifyUser
 *
 * @property int $id
 * @property string $email
 * @property string $role_id
 * @property string|null $data
 * @property int $status
 * @property string $verify_token
 * @property string $organization_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VerifyUser whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VerifyUser whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VerifyUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VerifyUser whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VerifyUser whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VerifyUser whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VerifyUser whereVerifyToken($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VerifyUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VerifyUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VerifyUser query()
 */
class VerifyUser extends Model
{
    public $timestamps = false;

    public function setDataAttribute($value)
    {
        $this->attributes['data'] = json_encode($value);
    }

    public function getDataAttribute($value)
    {
        return json_decode($value);
    }
}

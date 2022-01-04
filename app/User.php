<?php

namespace App;

use App\base\IStatus;
use App\repositories\UserRepository;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Role;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * App\User
 *
 * @property int $id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $middle_name
 * @property string $email
 * @property string|null $contact_no
 * @property int|null $address_id
 * @property int|null $bio
 * @property string $password
 * @property int $user_type_id
 * @property int $status_id
 * @property int $plan_id
 * @property string|null $verify_token
 * @property string $api_token
 * @property int $verify
 * @property string|null $notes
 * @property int|null $activate
 * @property string|null $data
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Address[] $addresses
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Organization[] $organizations
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Role[] $roles
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereActivate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereApiToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereContactNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUserTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereVerify($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereVerifyToken($value)
 * @property string|null $timezone_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereTimezoneId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @property string|null $last_login
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLastLogin($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\UserLastLogin[] $lastLogins
 * @property string|null $reset_password_sent_date_time
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereResetPasswordSentDateTime($value)
 * @property-read int|null $addresses_count
 * @property-read int|null $last_logins_count
 * @property-read int|null $notes_count
 * @property-read int|null $notifications_count
 * @property-read int|null $organizations_count
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\MemberViewLog[] $memberViewLogs
 * @property-read int|null $member_view_logs_count
 */

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name','last_name', 'email', 'password','verify_token','api_token','middle_name',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','verify_token'
    ];

    /**
         * Belongs To many relation with roles.
     * return array of roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(){
        return $this->belongsToMany('App\Role');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organizations()
    {
        return $this->hasMany('App\Organization');
    }

    public function lastLogins()
    {
        return $this->hasMany(UserLastLogin::class);
    }

    public function addresses()
    {
        return $this->hasMany('App\Address');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function memberViewLogs()
    {
        return $this->hasMany(MemberViewLog::class);
    }


    public function hasAnyRole($roles)
    {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role))
                {
                    return true;
                }
            }
        } else {
            if ($this->hasRole($roles)) {
                return true;
            }
        }
        return false;
    }

    public function hasRole($role)
    {
//        if ($this->roles()->wherePivot('current',IStatus::ACTIVE)->where('role_id', $role)->first()) {
        if($this->roles()->whereRoleId($role)->first()){
            return true;
        }
        return false;
    }

    public function getCurrentRole()
    {
        $roles = $this->roles;
        if (count($roles) >= 2) {
            return $this->roles()->wherePivot('current', '=',IStatus::ACTIVE)->wherePivot('status','!=',IStatus::INACTIVE)->first();
        }
        return $this->roles()->wherePivot('current','=', IStatus::ACTIVE)->wherePivot('status','!=',IStatus::INACTIVE)->first();
    }

    /**
     * @param $role_id
     *
     * @return bool|Role
     */
    public function setCurrentRole($role_id)
    {
        if ($this->hasRole($role_id)) {
            $attached = $this->attatchedRolesIds();
            $this->roles()->detach();
            $this->roles()->attach($attached);
//            $this->roles()->sync($attatched);
            $this->roles()->updateExistingPivot($role_id, [
                'current' => IStatus::ACTIVE,
            ]);
            return Role::find($role_id);
        }else{
            $attached = $this->attatchedRolesIds();
            $this->roles()->detach();
            $this->roles()->attach($attached);
            $this->roles()->attach($role_id);
            $this->roles()->updateExistingPivot($role_id, [
                'current' => IStatus::ACTIVE,
            ]);
        }
        return false;
    }


    /**
     * @return array
     */
    public function attatchedRolesIds()
    {
        $roles = $this->roles;
        $attatched = [];
        $count = 0;
        foreach ($roles as $role) {
            $attatched[$count] = $role->id;
            $count++;
        }
        return $attatched;
    }


    /**
     * Get role_id and tel either it is current or not.
     *
     * @param $role_id
     *
     * @return boolean
     */
    public function isCurrentRole($role_id)
    {
        if (empty($this->roles))
            return false;
        $role = $this->roles()->wherePivot('current', IStatus::ACTIVE)->wherePivot('role_id', $role_id)->first();
        if (!empty($role)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Sending User Password Reset Email By Sendgrid Api.
     * @param string $token
     */
    public function sendPasswordResetNotification($token){
        /** @var UserRepository $userRepository */
        $userRepository = new UserRepository();
        $userRepository->sendResetPasswordEmail($this, $token);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
}

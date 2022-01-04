<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\MemberProfile
 *
 * @property int $id
 * @property string|null $first_name
 * @property string|null $middle_name
 * @property string|null $last_name
 * @property string|null $contact_no
 * @property string|null $date_of_birth
 * @property string|null $validate_id
 * @property string|null $email
 * @property string|null $title
 * @property string|null $facebook_id
 * @property string|null $known_as
 * @property string|null $gender
 * @property string|null $phone
 * @property string|null $password
 * @property int|null $member_id
 * @property int|null $physical_address_id
 * @property int|null $postal_address_id
 * @property string|null $identity
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $verify_token
 * @property string|null $email_to_change
 * @property string|null $country_code
 * @property-read \App\Member|null $member
 * @property-read \App\Address|null $physicalAddress
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberProfile whereContactNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberProfile whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberProfile whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberProfile whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberProfile whereEmailToChange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberProfile whereFacebookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberProfile whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberProfile whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberProfile whereIdentity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberProfile whereKnownAs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberProfile whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberProfile whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberProfile whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberProfile wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberProfile wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberProfile wherePhysicalAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberProfile wherePostalAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberProfile whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberProfile whereValidateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberProfile whereVerifyToken($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberProfile query()
 * @property string|null $verify_link_sent_date_time
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MemberProfile whereVerifyLinkSentDateTime($value)
 * @property-read \App\Address|null $postalAddress
 */
class MemberProfile extends Model
{
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'contact_no',
        'date_of_birth',
        'validate_id',
        'email',
        'title',
        'facebook_id',
        'known_as',
        'gender',
        'phone',
        'password',
        'physical_address_id',
        'postal_address_id',
        'identity',
    ];


    protected $hidden = [
        'email_to_change',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function physicalAddress()
    {
        return $this->belongsTo(Address::class,'physical_address_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function postalAddress()
    {
        return $this->belongsTo(Address::class,'postal_address_id','id');
    }


}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\OrganizationOption
 *
 * @property int $id
 * @property int|null $activity
 * @property int|null $rsa
 * @property int|null $group
 * @property int|null $interest
 * @property int|null $status
 * @property int|null $organization_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int|null $proposer
 * @property int|null $personal
 * @property int|null $genealogy
 * @property int|null $sms
 * @property-read \App\Organization|null $organization
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationOption whereActivity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationOption whereGenealogy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationOption whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationOption whereInterest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationOption whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationOption wherePersonal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationOption whereProposer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationOption whereRsa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationOption whereSms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationOption whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationOption whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrganizationOption query()
 */
class OrganizationOption extends Model
{
    protected $fillable = [
        'organization_id',
    ];
    public static function authorizedFields()
    {
        return [
            'rsa','activity','group','interest','proposer','personal','genealogy','sms'
        ];
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\UntillSetting
 *
 * @property int $id
 * @property int $organization_id
 * @property string|null $url
 * @property string|null $port
 * @property string|null $username
 * @property string|null $password
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UntillSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UntillSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UntillSetting whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UntillSetting wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UntillSetting wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UntillSetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UntillSetting whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UntillSetting whereUsername($value)
 * @mixin \Eloquent
 * @property-read \App\Organization $organization
 * @method static \Illuminate\Database\Eloquent\Builder|UntillSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UntillSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UntillSetting query()
 */
class UntillSetting extends Model
{
    protected $fillable = [
        'organization_id','url','password','username','port'
    ];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}

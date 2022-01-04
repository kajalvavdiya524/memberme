<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\OfficeUse
 *
 * @property int $id
 * @property int|null $kiosk
 * @property int $organization_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Organization $organization
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OfficeUse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OfficeUse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OfficeUse whereKiosk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OfficeUse whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OfficeUse whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeUse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeUse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeUse query()
 */
class OfficeUse extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}

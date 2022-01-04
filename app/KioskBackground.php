<?php

namespace App;

use App\base\IStatus;
use Illuminate\Database\Eloquent\Model;

/**
 * Class KioskBackground
 *
 * @package App
 * @property $orientation
 * @property $url
 * @property $created_at
 * @property $updated_at
 * @property $label
 * @property $id
 * @property string|null $data
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Organization[] $organization
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskBackground whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskBackground whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskBackground whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskBackground whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskBackground whereOrientation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskBackground whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskBackground whereUrl($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskBackground newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskBackground newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskBackground query()
 * @property int|null $organization_id
 * @property-read \App\Organization $specificOrganization
 * @method static \Illuminate\Database\Eloquent\Builder|\App\KioskBackground whereOrganizationId($value)
 * @property-read int|null $organization_count
 */
class KioskBackground extends Model
{
    const ORIENTATION = [
        'PORTRAIT' => IStatus::ACTIVE,
        'LANDSCAPE' => IStatus::INACTIVE,
    ];

    const DEFAULT_BACKGROUND_COUNT = 32;
    const ORGANIZATION_BACKGROUND_COUNT = 32;

    public function organization()
    {
        return $this->belongsToMany(Organization::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function specificOrganization()
    {
        return $this->belongsTo(Organization::class);
    }
}

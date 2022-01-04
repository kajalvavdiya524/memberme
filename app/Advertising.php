<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Advertising
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string|null $name
 * @property int $organization_id
 * @property float|null $delay
 * @property string|null $image
 * @property string|null $animation
 * @property string|null $sound
 * @property float|null $duration
 * @property int $status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property mixed $advertising_images
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Advertising whereAnimation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Advertising whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Advertising whereDelay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Advertising whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Advertising whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Advertising whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Advertising whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Advertising whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Advertising whereSound($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Advertising whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Advertising whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\AdvertisingImage[] $advertisingImages
 * @property int|null $template_no
 * @property string|null $template_label
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Advertising whereTemplateLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Advertising whereTemplateNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Advertising newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Advertising newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Advertising query()
 * @property-read int|null $advertising_images_count
 */
class Advertising extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function advertisingImages()
    {
        return $this->hasMany(AdvertisingImage::class);
    }
}

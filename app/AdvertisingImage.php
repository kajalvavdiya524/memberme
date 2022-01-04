<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\AdvertisingImages
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string|null $url
 * @property int $advertising_id
 * @property string|null $animation
 * @property string|null $sound
 * @property float|null $duration
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property mixed $advertising
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AdvertisingImage whereAdvertisingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AdvertisingImage whereAnimation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AdvertisingImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AdvertisingImage whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AdvertisingImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AdvertisingImage whereSound($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AdvertisingImage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AdvertisingImage whereUrl($value)
 * @property string|null $name
 * @property int $sequence
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AdvertisingImage whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AdvertisingImage whereSequence($value)
 * @property string|null $sound_name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AdvertisingImage whereSoundName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AdvertisingImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AdvertisingImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AdvertisingImage query()
 */
class AdvertisingImage extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function advertising()
    {
        return $this->belongsTo(Advertising::class);
    }
}

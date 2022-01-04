<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Country
 *
 * @property int $id
 * @property string $name
 * @property string $country_code
 * @property string $country_short_name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Country whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Country whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Country whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Country whereUpdatedAt($value)
 * @property-read mixed $name_code
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Country whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Country whereCountryShortName($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Country newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Country newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Country query()
 */

class Country extends Model
{
    protected $appends = [
        'name_code'
    ];

    public function getNameCodeAttribute()
    {
        return $this->name. ' +'. $this->country_code;
    }


    public function getCountryCodeAttribute($value)
    {
        $trimedValue = ltrim($value,'+');
        return '+'.$trimedValue;
    }

}

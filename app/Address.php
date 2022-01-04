<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


/**
 * App\Address
 *
 * @property int $id
 * @property string|null $address1
 * @property string|null $address2
 * @property string|null $suburb
 * @property string|null $postal_code
 * @property string|null $city
 * @property string|null $region
 * @property int $country_id
 * @property string|null $latitude
 * @property string|null $longitude
 * @property string $status_id
 * @property int $item_id
 * @property int $address_type_id
 * @property int $item_type_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property mixed $country
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereAddress1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereAddress2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereAddressTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereItemTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereSuburb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address query()
 */

class Address extends Model
{
    protected $fillable = [
        'address_type_id' ,
        'address1',
        'address2',
        'suburb',
        'city',
        'region',
        'latitude',
        'longitude',
        'latitude',
        'status_id',
        'postal_code',
        'item_id',
        'address_type_id',
        'item_type_id',
        'country_id'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}

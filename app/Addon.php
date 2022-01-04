<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Addon
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $quantity
 * @property float|null $amount
 * @property int|null $duration
 * @property string|null $duration_type
 * @property int|null $is_recursive
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Addon whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Addon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Addon whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Addon whereDurationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Addon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Addon whereIsRecursive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Addon whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Addon whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Addon whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Addon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Addon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Addon query()
 */
class Addon extends Model
{
    //
}

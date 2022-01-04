<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Feature
 *
 * @package App
 * @property \Carbon\Carbon $created_at
 * @property int $id
 * @property int $name
 * @property int $status
 * @property \Carbon\Carbon $updated_at
 * @property float|null $amount_per_month
 * @property int $is_quantitative
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feature whereAmountPerMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feature whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feature whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feature whereIsQuantitative($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feature whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feature whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feature whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feature newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feature newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feature query()
 */
class Feature extends Model
{
    //
}

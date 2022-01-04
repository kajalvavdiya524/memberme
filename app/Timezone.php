<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Timezone
 *
 * @property int $id
 * @property string|null $currency
 * @property string|null $timezone
 * @property string|null $territory
 * @property string|null $other
 * @property string|null $status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Timezone whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Timezone whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Timezone whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Timezone whereOther($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Timezone whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Timezone whereTerritory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Timezone whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Timezone whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Timezone newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Timezone newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Timezone query()
 */
class Timezone extends Model
{
    const PACIFIC_AUCKLAND = 'Pacific/Auckland';
}

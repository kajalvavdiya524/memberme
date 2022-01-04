<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\DrawPrize
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $drawn_date_time
 * @property int|null $member_id
 * @property int $draw_id
 * @property int $organization_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Draw $draw
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DrawPrize whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DrawPrize whereDrawId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DrawPrize whereDrawnDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DrawPrize whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DrawPrize whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DrawPrize whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DrawPrize whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DrawPrize whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DrawPrize newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DrawPrize newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DrawPrize query()
 */
class DrawPrize extends Model
{
    protected $fillable = [
        'name','draw_id', 'organization_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function draw(){
        return $this->belongsTo(Draw::class);
    }
}

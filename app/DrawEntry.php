<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\DrawEntry
 *
 * @property int $id
 * @property int $draw_id
 * @property int $organization_id
 * @property int $member_id
 * @property string|null $entry_date_time
 * @property int $status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property mixed $member
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DrawEntry whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DrawEntry whereDrawId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DrawEntry whereEntryDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DrawEntry whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DrawEntry whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DrawEntry whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DrawEntry whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DrawEntry whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DrawEntry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DrawEntry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DrawEntry query()
 */
class DrawEntry extends Model
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\SendgridSetting
 *
 * @property int $id
 * @property int|null $organization_id
 * @property string|null $api_key
 * @property string $key_added_datetime
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property mixed $organization
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SendgridSetting whereApiKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SendgridSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SendgridSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SendgridSetting whereKeyAddedDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SendgridSetting whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SendgridSetting whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|SendgridSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SendgridSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SendgridSetting query()
 */
class SendgridSetting extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}

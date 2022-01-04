<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\ChangeLog
 *
 * @property int $id
 * @property string|null $field_name
 * @property string|null $old_value
 * @property string|null $new_value
 * @property string|null $model
 * @property string $changed_date_time
 * @property int|null $entity_id
 * @property int|null $type
 * @property int|null $status
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|ChangeLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChangeLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChangeLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|ChangeLog whereChangedDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChangeLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChangeLog whereEntityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChangeLog whereFieldName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChangeLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChangeLog whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChangeLog whereNewValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChangeLog whereOldValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChangeLog whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChangeLog whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChangeLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChangeLog whereUserId($value)
 * @mixin \Eloquent
 */
class ChangeLog extends Model
{
    use HasFactory;

    const TYPE = [
        'MEMBER' => 1
    ];

    const FIELD = [
        'PHYSICAL_ADDRESS' => 'physical address',
        'POSTAL_ADDRESS' => 'physical address'
    ];
    protected  $fillable = [
        'field_name', 'old_value','new_value','model','type','status','entity_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Record
 *
 * @property string $name
 * @property int $record_type_id
 * @property int $status
 * @property string|null $data
 * @property int|null $parent_id
 * @property int $id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Record whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Record whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Record whereId($value)* @method static \Illuminate\Database\Eloquent\Builder|\App\Record whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Record whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Record whereRecordTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Record whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Record whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Record newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Record newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Record query()
 * @property int|null $organization_id
 * @method static \Illuminate\Database\Eloquent\Builder|Record whereOrganizationId($value)
 */
class Record extends Model
{
    protected $hidden = [
//        'data',
    ];
    protected $fillable = [
      'name' ,'data','record_type_id','organization_id'
    ];

    public function setDataAttribute($value)
    {
        $this->attributes['data'] = serialize_data($value);
    }
    public function getDataAttribute()
    {
        if ($this->attributes['data']) {
            return un_serialize_data($this->attributes['data']);
        }
        return null;
    }
}

<?php

namespace App;

use App\base\IStatus;
use DB;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Draw
 *
 * @property int $id
 * @property int $organization_id
 * @property string|null $name
 * @property int|null $frequency
 * @property string|null $duration_start
 * @property string|null $duration_finish
 * @property int|null $frequency_limit
 * @property int|null $frequency_limit_quantity
 * @property int|null $frequency_limit_quantity_period
 * @property int|null $entry_limit
 * @property int|null $entry_limit_quantity
 * @property int|null $print_entry
 * @property string|null $status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property mixed $entries
 * @property mixed $prizes
 * @property int $total_entries
 * @property \Illuminate\Database\Eloquent\Collection $unique_entries
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Draw whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Draw whereDurationFinish($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Draw whereDurationStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Draw whereEntryLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Draw whereEntryLimitQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Draw whereFrequency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Draw whereFrequencyLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Draw whereFrequencyLimitQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Draw whereFrequencyLimitQuantityPeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Draw whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Draw whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Draw whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Draw wherePrintEntry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Draw whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Draw whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Draw newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Draw newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Draw query()
 * @property-read int|null $entries_count
 * @property-read int|null $prizes_count
 * @property-read \App\DrawPrize|null $prize
 */
class Draw extends Model
{
    const DRAW_DAYS = [
        'ALL' => 1,
        'MONDAY' => 2,
        'TUESDAY' => 3,
        'WEDNESDAY' => 4,
        'THURSDAY' => 5,
        'FRIDAY' => 6,
        'SATURDAY' => 7,
         'SUNDAY' => 8
    ];
    const FREQUENCY = [
        'OFF' => IStatus::ACTIVE,
        'RECURRING' => IStatus::INACTIVE,
    ];
    const FREQUENCY_LIMIT = [
        'YES' => IStatus::ACTIVE,
        'NO' => IStatus::INACTIVE,
    ];
    const FREQUENCY_LIMIT_PERIOD = [
        'DRAW' => 1,
        'HOURS' => 2,
        'DAYS' => 3,
        'WEEKS' => 4,
        'MONTHS' => 5,
    ];

    const ENTRY_LIMIT = [
        'YES' => IStatus::ACTIVE,
        'NO' => IStatus::INACTIVE,
    ];
    const PRINT_ENTRY = [
        'YES' => IStatus::ACTIVE,
        'NO' => IStatus::INACTIVE,
    ];

    protected $fillable = [
        'name','frequency','frequency_limit','frequency_limit_quantity', 'frequency_limit_quantity_period','print_entry','entry_limit_quantity','entry_limit',
    ];

    protected $appends = [
        'total_entries', 'unique_entries'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function prizes()
    {
        return $this->hasMany(DrawPrize::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function entries()
    {
        return $this->hasMany(DrawEntry::class);
    }

    public function prize()
    {
        return $this->hasOne(DrawPrize::class);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function addPrize($name)
    {
        $prize = DrawPrize::create([
            'name' => $name,
            'draw_id' => $this->id,
            'organization_id' => $this->organization_id,
        ]);

        return $prize;
    }

    /**
     * return the number of total entries against each draw.
     * @return int
     */
    public function getTotalEntriesAttribute()
    {
        return $this->entries()->count();
    }

    public function setDrawDaysAttribute($value)
    {
        $this->attributes['draw_days'] = json_encode($value);
    }

    public function getDrawDaysAttribute()
    {
        return (empty($this->attributes['draw_days']))? [] : json_decode($this->attributes['draw_days']);
    }

    /**
     * Return the no of unique entries against each draw.
     *
     * @return int
     */
    public function getUniqueEntriesAttribute()
    {
        $query = "SELECT count( * ) as unique_entries from  ( select * from draw_entries WHERE draw_id = $this->id group by member_id ) As d_e";
        $result = DB::Select($query);
        $count = 0;
        foreach ($result as $item) {
            $count = $item->unique_entries;
        }
        return $count;
    }
}

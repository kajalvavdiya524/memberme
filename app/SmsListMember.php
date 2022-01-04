<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SmsListMember
 *
 * @package App
 * @property $group_id
 * @property $sms_list_id
 * @property $member_id
 * @property $optout_date_time
 * @property $id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\SmsList $smsList
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SmsListMember whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SmsListMember whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SmsListMember whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SmsListMember whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SmsListMember whereOptoutDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SmsListMember whereSmsListId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SmsListMember whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SmsListMember newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SmsListMember newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SmsListMember query()
 */
class SmsListMember extends Model
{

    public function smsList(){
        return $this->belongsTo(SmsList::class);
    }
}

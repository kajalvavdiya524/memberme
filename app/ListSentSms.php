<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ListSentSms
 *
 * @package App
 * @property $organization_id
 * @property $sms_list_id
 * @property $message
 * @property $recipients
 * @property $cost
 * @property $sms
 * @property $sent_date_time
 * @property $delivered
 * @property $pending
 * @property $bounced
 * @property $responses
 * @property $optouts
 * @property $created_at
 * @property $updated_at
 * @property int $id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ListSentSms whereBounced($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ListSentSms whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ListSentSms whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ListSentSms whereDelivered($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ListSentSms whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ListSentSms whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ListSentSms whereOptouts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ListSentSms whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ListSentSms wherePending($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ListSentSms whereRecipients($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ListSentSms whereResponses($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ListSentSms whereSentDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ListSentSms whereSms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ListSentSms whereSmsListId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ListSentSms whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ListSentSms newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ListSentSms newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ListSentSms query()
 */
class ListSentSms extends Model
{
    //
}

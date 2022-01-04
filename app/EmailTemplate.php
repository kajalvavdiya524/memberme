<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Self_;

/**
 * App\EmailTemplate
 *
 * @property int $id
 * @property int $organization_id
 * @property string|null $email_name
 * @property string|null $template_id
 * @property string|null $data
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property mixed $organization
 * @property mixed $groups
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailTemplate whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailTemplate whereEmailName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailTemplate whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailTemplate whereTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailTemplate whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Subscription[] $subscriptions
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailTemplate memberPayment()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailTemplate newMember()
 * @property int|null $send_email_date
 * @property int|null $days_before_date
 * @property int|null $days_after_date
 * @property string|null $send_email_time
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailTemplate whereDaysAfterDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailTemplate whereDaysBeforeDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailTemplate whereSendEmailDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailTemplate whereSendEmailTime($value)
 * @property int|null $email_type
 * @property int|null $event
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailTemplate whereEmailType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailTemplate whereEvent($value)
 * @property int|null $before_or_after
 * @property int|null $days
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailTemplate whereBeforeOrAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailTemplate whereDays($value)
 * @property int|null $email_group
 * @property-write mixed $email_group_value
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailTemplate whereEmailGroup($value)
 * @property int|null $status
 * @property int|null $is_valid
 * @property string|null $invalid_reason
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailTemplate whereInvalidReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailTemplate whereIsValid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailTemplate whereStatus($value)
 * @property string $send_from
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailTemplate whereSendFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailTemplate memberResetPassword()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmailTemplate userResetPassword()
 * @property-read int|null $groups_count
 * @property-read int|null $subscriptions_count
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailTemplate query()
 */
class EmailTemplate extends Model
{
    protected $fillable = ['email_name'];
    protected $appends = ['email_group_value'];

    const TYPE = [
        'NEW_MEMBER' => 'New Member',
        'MEMBER_PAYMENT' => 'Member Payment',
    ];

    const SEND_EMAIL_DATE = [
        'MEMBER_EXPIRY_DATE' => 1,
        'MEMBER_CREATED_AT' => 2,
        'MEMBER_DATE_OF_BIRTH' => 3,	
    ];

    const EMAIL_TYPE = [
        'TRANSACTIONAL' => 1,
        'SCHEDULED' => 2,
    ];

    const BEFORE_OR_AFTER = [
        'BEFORE' => 1,
        'AFTER' => 2,
        'DAY_OF' => 3,
    ];

    const EMAIL_GROUP = [
        'SUBSCRIPTION' => 1,
        'GROUPS' => 2,
    ];

    const EVENT = [
        'NEW_MEMBER' => 1,
        'MEMBER_PAYMENT' => 2,
        'MEMBER_RESET_PASSWORD' => 4,
        'USER_RESET_PASSWORD' => 5,
        'OTHER' => 3,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'email_template_group', 'email_template_id', 'group_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function subscriptions()
    {
        return $this->belongsToMany(Subscription::class, 'email_template_subscription', 'email_template_id', 'subscription_id');
    }



    public function scopeNewMember($query)
    {
        return $query->where('event', EmailTemplate::EVENT['NEW_MEMBER']);
    }

    public function scopeMemberPayment($query)
    {
        return $query->where('event', EmailTemplate::EVENT['MEMBER_PAYMENT']);
    }

    public function scopeMemberResetPassword($query)
    {
        return $query->where('event', EmailTemplate::EVENT['MEMBER_RESET_PASSWORD']);
    }

    public function scopeUserResetPassword($query)
    {
        return $query->where('event', EmailTemplate::EVENT['USER_RESET_PASSWORD']);
    }

    public function getEmailGroupValueAttribute()
    {
        $emailGroupValue = null;

        if($this->email_group == EmailTemplate::EMAIL_GROUP['SUBSCRIPTION']){
            $subscriptions = $this->subscriptions()->select('title')->get();
            foreach ($subscriptions as $subscription) {
                if($emailGroupValue == null ) {
                    $emailGroupValue = $subscription->title;
                }else{
                    $emailGroupValue .= ', '.$subscription->title;
                }
            }
        }else{
            $groups = $this->groups()->select('name')->get()->toArray();
            foreach ($groups as $group) {
                if($emailGroupValue == null ) {
                    $emailGroupValue = $group['name'];
                }else{
                    $emailGroupValue .= ', '.$group['name'];
                }
            }
        }
        return $emailGroupValue;
    }

}

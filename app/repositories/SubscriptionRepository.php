<?php
/**
 * Created by PhpStorm.
 * User: Faisal
 * Date: 2/20/2018
 * Time: 10:15 PM
 */

namespace App\repositories;


use App\base\IStatus;
use App\Organization;
use App\Subscription;

class SubscriptionRepository
{
    /**
     * @param $data
     * @return Subscription
     */
    public function create($data)
    {
        /* @var $subscription Subscription*/
        $subscription = [];
        if(!empty($data)){
            if(!empty(array_get($data,'id'))){
                $subscription = Subscription::find(array_get($data,'id'));
            }
            if(empty($subscription)){
                $subscription = new Subscription();
            }

            $subscription->organization_id = array_get($data,'organization_id');
            $subscription->title = array_get($data,'title');
            $subscription->joining_fee = array_get($data,'joining_fee');
            $subscription->subscription_fee = array_get($data,'subscription_fee');
            $subscription->expires = array_get($data,'expires');
            $subscription->expiry_duration = array_get($data,'expiry_duration');
            $subscription->expiry_quantity = array_get($data,'expiry_quantity');
            $subscription->expiry_date_option = array_get($data,'expiry_date_option');
            $subscription->expiry_term = array_get($data,'expiry_term');
            $subscription->expiry = array_get($data,'expiry');
            $subscription->overdue = array_get($data,'overdue');
            $subscription->overdue_duration = array_get($data,'overdue_duration');
            $subscription->overdue_term = array_get($data,'overdue_term');
            $subscription->pro_rata = array_get($data,'pro_rata');

            //region New fields
            $subscription->pro_rata_date = array_get($data,'pro_rata_date');
            $subscription->send_invoice = array_get($data,'send_invoice');
            $subscription->send_invoice_date = array_get($data,'send_invoice_date');
            $subscription->overdue_days = array_get($data,'overdue_days');
            $subscription->overdue_fee = array_get($data,'overdue_fee');
            $subscription->pro_rata_birthday = array_get($data,'pro_rata_birthday');
            if(array_get($data,'auto_assign') == IStatus::ACTIVE ){
                $organization = Organization::find(array_get($data,'organization_id'));
                if(!empty($organization)){
                    $organization->subscriptions()->update(['auto_assign' => IStatus::INACTIVE]);
                }
            }
            $subscription->auto_assign = array_get($data,'auto_assign');
            /*$subscription->start_date_term = array_get($data,'start_date_term');
            $subscription->renewal_date_term = array_get($data,'renewal_date_term');*/
            $subscription->payment_reminder = array_get($data,'payment_reminder');
            $subscription->payment_reminder_term = array_get($data,'payment_reminder_term');
            $subscription->due_duration = array_get($data,'due_duration');
            //endregion

            $subscription->amount = array_get($data,'amount');
            $subscription->frequency = array_get($data,'frequency');
            $subscription->late_payment = array_get($data,'late_payment');
            $subscription->role = array_get($data,'role');
            $subscription->role_id = array_get($data,'role_id');
            $subscription->status = IStatus::ACTIVE;
            $subscription->data = $this->prepareData($data);
            $subscription->save();
            return $subscription;
        }
        return $subscription;
    }

    public function prepareData($data)
    {
        if(isset($data['data'])){
            return json_encode($data['data']);
        }
        return null;
    }

    public function changeField(Subscription $subscription, $field, $value = null)
    {
        if($field  == 'auto_assign'){
            /* @var $organization Organization */
            $organization = $subscription->organization;
            if(!empty($organization)) $organization->subscriptions()->update(['auto_assign' => IStatus::INACTIVE]);
        }
        $subscription->$field = $value;
        $subscription->save();
        return $subscription;
    }

    public function getStats(Organization $organization)
    {
        return $organization->subscriptions;
    }
}
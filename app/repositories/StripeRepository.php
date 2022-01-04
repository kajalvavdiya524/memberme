<?php
/**
 * Created by PhpStorm.
 * User: Faisal
 * Date: 11/29/2018
 * Time: 12:44 AM
 */

namespace App\repositories;


use App\base\IStatus;
use App\Plan;
use Cartalyst\Stripe\Laravel\Facades\Stripe;

class StripeRepository
{
    /**
     * Sync All the Stripe Plans
     */
    public function syncPlans()
    {
        $response = Stripe::plans()->all();
        $stripePlans = array_get($response, 'data');
        $stripePlans = array_reverse($stripePlans);
        foreach ($stripePlans as $stripePlan) {
            $plan = Plan::where('ref_id', array_get($stripePlan, 'id'))->first();
            if (empty($plan)) {
                $plan = new Plan();
            }

            $plan->ref_id = array_get($stripePlan,'id');
            $plan->name = array_get($stripePlan, 'name');
            $plan->amount = (array_get($stripePlan,'amount'))?number_format(array_get($stripePlan,'amount')/100,2):0;
            $plan->nickname = array_get($stripePlan, 'nickname');
            $plan->interval = array_get($stripePlan, 'interval');
            $plan->interval_count = array_get($stripePlan, 'interval_count');
            $plan->product = array_get($stripePlan, 'product');
            $plan->tiers = array_get($stripePlan, 'tiers');
            $plan->tiers = array_get($stripePlan, 'tiers');
            $plan->tiers_mode = array_get($stripePlan, 'tiers_mode');
            $plan->transform_usage = array_get($stripePlan, 'transform_usage');
            $plan->trial_period_days = array_get($stripePlan, 'trial_period_days');
            $plan->billing_scheme = array_get($stripePlan, 'billing_scheme');
            $plan->currency = array_get($stripePlan, 'currency');
            $plan->status = (array_get($stripePlan, 'active'))? IStatus::ACTIVE :IStatus::INACTIVE;
            $plan->save();
        }
    }


    /**
     * @param $email
     * @return mixed
     */
    public function createCustomerInStripe($email)
    {
        $customer = \Stripe::customers()->create([
            'email' => $email,
        ]);

        return $customer;
    }

    /**
     * @param $stripeCustomerId
     * @param $stripeTokenId
     * @return mixed
     */
    public function createPaymentCard($stripeCustomerId, $stripeTokenId)
    {
        $card = Stripe::cards()->create($stripeCustomerId, $stripeTokenId);

        return $card;
    }

    /**
     * @param $stripeCustomerId
     * @param $stripePlanName
     * @return mixed
     */
    public function createSubscription($stripeCustomerId, $stripePlanName)
    {
        $subscription = \Stripe::subscriptions()->create($stripeCustomerId, [
            'plan' => $stripePlanName,
        ]);

        return $subscription;
    }


}
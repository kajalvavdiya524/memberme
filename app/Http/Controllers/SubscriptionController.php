<?php

namespace App\Http\Controllers;

use App\base\IResponseCode;
use App\base\IStatus;
use App\Http\Requests\Subscription\SubscriptionCreateRequest;
use App\Http\Requests\Subscription\SubscriptionUpdateRequest;
use App\Organization;
use App\repositories\SubscriptionRepository;
use App\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /* @var $subscriptionRepo SubscriptionRepository*/
    public $subscriptionRepo;
    public function __construct()
    {
        $this->subscriptionRepo = new SubscriptionRepository();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /* @var $organization Organization*/
        $organization = $request->get('organization');
        $subscriptions = $organization->subscriptions()->where('status' ,'!=', IStatus::INACTIVE)->get();
        return api_response($subscriptions,null,'Subscriptions');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /* @var $organization Organization*/
        $organization = $request->get('organization');
        $validator = Validator($request->all(),SubscriptionCreateRequest::getRules());
        if (!$validator->fails()) {
            $subscription = $this->subscriptionRepo->create($request->all());
            return api_response($subscription, null,'Subsciption Created Successfully',IResponseCode::SUCCESS);
        } else {
            return api_response(null, $validator->errors(), IResponseCode::INVALID_PARAMS);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id , Request $request)
    {
        /* @var $organization Organization*/
        $organization = $request->get('organization');

        $subscription = $organization->subscriptions()->find($id);
        if(!empty($subscription)){
            return api_response($subscription,null,'Subscription Details');
        }
        return api_response(null,['id' => 'Subscription not found against this id']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeField($id, Request $request)
    {
        /* @var $organization Organization*/
        $organization = $request->get('organization');
        $subscription = $organization->subscriptions()->find($id);

        if(empty($subscription )){
            return api_response(null,['id' => 'Subscription not found']);
        }

        $fields = $request->input();

        //region If Fields is more then 1 return back
        if (count($fields) > 2) {
            return api_response(null, ['error' => 'You can not edit more then one field'], IResponseCode::PRECONDITION_FAILED);
        }
        //endregion

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
        ];

        $validator = Validator($request->all(), $validationRules);

        //region Validating and updating the fields
        $subscriptionFeild = null;
        $subscriptionFieldValue = null;
        $validKey = false;

        if (!$validator->fails()) {
            foreach ($fields as $key => $value) {
                if ($key != 'organization_id') {

                    if($subscription->members()->count() > 0 && $key != 'title'){
                        return api_response(null,['id' => 'Subscription Cannot be edited as this is assigned to a member']);
                    }

                    if (in_array($key, Subscription::AuthorizedFields())) {
                        $subscriptionFeild = $key;
                        $subscriptionFieldValue = $value;
                        $validKey = true;
                    } else {
                        $validator->getMessageBag()->add($key, 'Invalid Field Name');
                        return api_response(null, $validator->errors(), IResponseCode::INVALID_PARAMS);
                    }
                }
            }
            if($validKey){
                $result = $this->subscriptionRepo->changeField($subscription,$subscriptionFeild,$subscriptionFieldValue);
                return api_response($result);
            }else{
                return api_response(null,['invalid_field' => 'invalid field']);
            }

        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        /* @var $organization Organization*/
        $organization = $request->get(Organization::NAME);
        $validator = Validator($request->all(),SubscriptionUpdateRequest::getRules());
        if (!$validator->fails()) {

            $validator->after(function ($validator) {
                //todo After Validation here
            });

            if (!$validator->fails()) {
                $subscription = $this->subscriptionRepo->create($request->all());
                return api_response($subscription, null,'Subsciption Updated Successfully');
            } else {
                return api_error($validator->errors());
            }
        } else {
            return api_error($validator->errors());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,Request $request)
    {
        /* @var $organization Organization*/
        $organization = $request->get('organization');
        /* @var $subscription Subscription*/
        $subscription = $organization->subscriptions()->find($id);
        if(empty($subscription)){
            return api_response(null,['id' => 'Invalid id']);
        }

        if($subscription->members()->count()){
            $subscription->status = IStatus::INACTIVE;
            $subscription->save();
        } else {
            $subscription->forceDelete();
        }

//        $subscription->delete();
        return api_response(null,null,'Subscription Deleted Successfully');
    }
}

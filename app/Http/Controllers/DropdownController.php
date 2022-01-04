<?php

namespace App\Http\Controllers;

use App\Country;
use App\Helpers\ApiHelper;
use App\Helpers\DropdownHelper;
use App\Organization;
use App\Timezone;
use Illuminate\Http\Request;

class DropdownController extends Controller
{
    public function getMemberTitleList(Request $request){
        $result = DropdownHelper::memberTitleList();
        return response(ApiHelper::apiResponse($result,null,'Member Title List'),200);
    }

    public function getMemberStatusList(Request $request){
        $result = DropdownHelper::memberStatusList();
        return api_response($result);
    }

    public function getTimeZoneList($name = null)
    {
        $resultSet = new Timezone();
        if(!empty($name)){
            $resultSet = $resultSet->where('timezone','like', '%'.$name.'%');
        }
        $result = $resultSet->pluck('timezone','id');
        return api_response($result);
    }

    public function getSubscriptions(Request $request)
    {
        $organization =  $request->get(Organization::NAME);

        $validationRules = [
            'organization_id' => 'required|exists:organizations,id',
        ];

        $validator = Validator($request->all(), $validationRules);

        if (!$validator->fails()) {

            $validator->after(function ($validator) {
                //todo After Validation here
            });

            if (!$validator->fails()) {
                $subscriptionList = $organization->subscriptions()->pluck('title','id');
                return api_response($subscriptionList);
            } else {
                return api_error($validator->errors());
            }

        } else {
            return api_error($validator->errors());
        }
    }

    public function getPaymentTypesDropDown( Request $request)
    {
        /* @var $organization Organization*/
        $organization =  $request->get(Organization::NAME);
        $list = $organization->paymentTypes()->pluck('name','name');
        return api_response($list);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPaymentTypesForPartPay(Request $request)
    {
        /* @var $organization Organization*/
        $organization =  $request->get(Organization::NAME);
        $list = $organization->paymentTypes()->pluck('name','id');
        return api_response($list);
    }

    /**
     * Country Drop down list.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCountryListing()
    {
        $countryCodeList = Country::orderBy('id', 'asc')
            ->get()->pluck('name','id')->toArray();
        return api_response($countryCodeList);
    }

    public function getPaymentFrequency()
    {
        return api_response(DropdownHelper::paymentFrequencyList());
    }

    public function getVoucherParameterTypes()
    {
        $voucherParameterTypes = DropdownHelper::getVoucherParameterTypes();
        return api_response($voucherParameterTypes);
    }
    public function getVoucherParameterExpires()
    {
        $voucherParameterExpires = DropdownHelper::getVoucherParameterExpires();
        return api_response($voucherParameterExpires);
    }
    public function getVoucherParameterExpiry()
    {
        $voucherParameterExpiry = DropdownHelper::getVoucherParameterExpiry();
        return api_response($voucherParameterExpiry);
    }
    public function getVoucherParameterLimitedQuantity(){
        $voucherParameterLimitedQuantity = DropdownHelper::getVoucherParameterLimitedQuantity();
        return api_response($voucherParameterLimitedQuantity);
    }
    public function getVoucherParameterValue(){
        $voucherParameterValue = DropdownHelper::getVoucherParameterValue();
        return api_response($voucherParameterValue);
    }
    public function getVoucherParameterValueMode(){
        $voucherParameterValueMode = DropdownHelper::getVoucherParameterValueMode();
        return api_response($voucherParameterValueMode);
    }
    public function getKioskVoucherParameterDuration(){
        $durationList = DropdownHelper::getKioskVoucherParameterDuration();
        return api_response($durationList);
    }
    public function getVoucherParameterFrequency(){
        $frequencyList = DropdownHelper::getKioskVoucherParameterFrequency();
        return api_response($frequencyList);
    }

    public function getEthnicityList(){
        $ethnicityList = DropdownHelper::getEthnicityList();
        return api_response($ethnicityList);
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: Faisal
 * Date: 5/5/2018
 * Time: 11:43 AM
 */

namespace App\Services;


use App\Exceptions\ApiException;
use App\Exceptions\BrustSms\MemberAlreadyInList;
use App\Exceptions\BrustSms\MemberNotExistInListException;
use App\Exceptions\BrustSms\SmsListAlreadyExistsException;
use App\Organization;

class BrustsmsService
{
    /* @var $brustsmsClient BrustsmsClient*/
    public $brustsmsClient;

    public function setup(Organization $organization)
    {
        $smsSetting = $organization->smsSetting;
        if($smsSetting){
            $this->brustsmsClient = new BrustsmsClient($smsSetting->api_key,$smsSetting->api_secret);
        }else{
            throw new ApiException(null,['error' => 'Sms Api isn\'t setup. ']);
        }
    }

    public function __call($method, $arguments)
    {
        if (method_exists($this, $method)) {
            $response = call_user_func_array(array($this, $method), $arguments);

            if($method == 'addToList' && !empty($response->error->code) && $response->error->code == 'KEY_EXISTS'){
                throw new MemberAlreadyInList($response->error->description);
            }

            if($method == 'removeFromList' && !empty($response->error->code) && $response->error->code == 'NOT_FOUND'){
                throw new MemberNotExistInListException($response->error->description);
            }

            if($method == 'createList' && !empty($response->error->code) && $response->error->code == 'KEY_EXISTS'){
                throw new SmsListAlreadyExistsException($response->error->code);
            }


            if(empty($response)){
                \Log::error('Response is null '. $method);
                throw  new ApiException(null,['error' => 'Something Went Wrong']);
            }

            $error = $response->error;
            switch ($error->code){
                case 'AUTH_FAILED_NO_DATA':
                case 'NOT_IMPLEMENTED':
                case 'AUTH_FAILED':
                case 'OVER_LIMIT':
                case 'FIELD_EMPTY':
                case 'FIELD_INVALID':
                case 'NO_ACCESS':
                case 'KEY_EXISTS':
                case 'NOT_FOUND':
                case 'ACCOUNT_EXPIRED':
                case 'FAILED':
                case 'REQUEST_FAILED':
                case 'LIST_EMPTY':
                case 'LEDGER_ERROR':
                case 'RECIPIENTS_ERROR':
                    throw  new ApiException(null,['error' => $error->description]);
                    break;
                case 'SUCCESS':
                    return $response;
                    break;
            }
            \Log::error('Response has no matching Error code');
            throw  new ApiException(null,['error' => 'Something Went Wrong']);
        }
    }

    private function getBalance(){
        return $this->brustsmsClient->getBalance();
    }

    private function createList($name, $customFields = []){
        return $this->brustsmsClient->addList($name,$customFields);
    }

    private function getLists(){
        $lists = $this->brustsmsClient->getLists();
        return $lists;
    }


    /**
     * @param $listId
     * @param $data must have number, first name and last name.
     * @return null/object from brust sms
     *
     * @throws ApiException
     */
    private function addMemberToList($listId,$data){
        $number  = array_get($data,'number');
        if(!empty($number)){
            return $this->brustsmsClient->addToList($listId,$number,array_get($data,'first_name'),array_get($data,'last_name'));
        }
        throw new ApiException(null,['error' => 'Number not found to insert in Sms List']);
    }

    /**
     * * Add bulk contacts to new list.
     * @param $name
     * @param $url
     * @return \stdClass
     *
     */
    private function addBulkContacts($name, $url, $fields, $countryCode){
        return $this->brustsmsClient->addContactsBulk($name,$url, $fields, $countryCode);
    }

    /**
     * Add single contact to existing list.
     *
     * @param $listId
     * @param $number
     * @param $firstName
     * @param $lastName
     * @return \stdClass
     */
    private function addToList($listId , $number,$firstName,$lastName,$fields ,$countryCode = null){
        return $this->brustsmsClient->addToList($listId, $number, $firstName, $lastName, $fields, $countryCode);
    }

    private function sendSmsToList($listId,$message)
    {
         return $this->brustsmsClient->sendSms($message,null,null,null,$listId,'','','','');
    }

    private function getSmsDetails($smsId)
    {
        return $this->brustsmsClient->getSms($smsId);
    }

    private function getDeliveryStatus($smsId, $number){
        return $this->brustsmsClient->getSmsDeliveryStatus($smsId, $number);
    }

    /**
     * This will return all the message details in which cost, delivery_stats , recipients will be available
     *
     * @param $smsId
     * @return \stdClass
     */
    private function getSmsSent($smsId){
        return $this->brustsmsClient->getSmsSent($smsId);
    }

    /**
     * @param $listId
     * @return \stdClass
     */
    private function getList($listId)
    {
        return $this->brustsmsClient->getList($listId);
    }

    /**
     * @param $listId
     * @param $number
     * @return \stdClass
     */
    private function removeFromList($listId, $number)
    {
        return $this->brustsmsClient->deleteFromList($listId,$number);
    }
}
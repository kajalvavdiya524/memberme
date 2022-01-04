<?php
/**
 * Created by PhpStorm.
 * User: Faisal
 * Date: 12/14/2019
 * Time: 8:22 PM
 */

namespace App\Services\Untill;


use App\Exceptions\ApiException;
use App\Organization;
use App\UntillSetting;
use Dompdf\Exception;
use phpDocumentor\Reflection\Types\Self_;

class UntillService
{
    const TEMP_USERNAME = 'tempUsername';
    const TEMP_PASSWORD = 'tempPass';

    const URN = [
        'GET_CLIENTS' => 'GetClients',
        'GET_CLIENT_EX' => 'GetClientsEx',
        'UPDATE_CLIENTS' => 'UpdateClients',
        'GET_CLIENT_CARDS' => 'GetClientCardsInfo',
        'GET_CLIENT_CARD_NAMES' => 'GetClientCardNamesInfo',
        'UPDATE_CLIENT_CARDS' => 'UpdateClientCards',
        'GET_CLIENT_BY_ID' => 'GetClientsEx',
        'ADD_CLIENT_SAVE_POINT' => 'AddClientSavepoints',
    ];

    const XSI_TYPE = [
        'GET_CLIENTS' => 'urn:urn:TGetClientsRequest',
        'GET_CLIENT_EX' => 'urn:TGetClientsExRequest',
        'UPDATE_CLIENTS'  => 'urn:TUpdateClientsRequest',
        'GET_CLIENT_CARDS'  => 'urn:TGetClientCardsInfoRequest',
        'GET_CLIENT_CARD_NAMES'  => 'urn:TGetClientCardNamesInfoRequest',
        'UPDATE_CLIENT_CARDS'  => 'urn:TUpdateClientCardsRequest',
        'GET_CLIENT_BY_ID'  => 'urn:TGetClientsExRequest',
        'ADD_CLIENT_SAVE_POINT'  => 'urn:TAddClientSavepointsRequest',
    ];
    /** @var  $client Client */
    public $client;

    public function __construct(Organization $organization)
    {
        /** @var UntillSetting $untillSetting */
        $untillSetting = $organization->untillSetting;

        if(empty($untillSetting)){
            throw new ApiException(null,['error' => 'Please Setup Pos Setting First']);
        }

        if(empty($untillSetting->username) || empty($untillSetting->password) || empty($untillSetting->url) || empty($untillSetting->port))
            throw new ApiException(null,['error' => 'Please Complete Pos Setup First']);

        $this->client = new \App\Services\Untill\Client($untillSetting->username, $untillSetting->password, 'http://'.$untillSetting->url, $untillSetting->port);
    }

    public function __call($method, $arguments)
    {
        if (method_exists($this, $method)) {
            $response = call_user_func_array(array($this, $method), $arguments);
        }
        $errorCode = array_get($response,'ReturnCode');
        if ($errorCode == 0) {
            if ($method == 'getClients') {
                return array_get(array_get($response, 'Clients', []), 'item', []);
            }

            if ($method == 'addOrUpdateClient') {
                return array_get(array_get(array_get($response, 'Extra'), 'item'), 'Value');
            }

            if ($method == 'getClientById') {
                return array_get(array_get($response, 'Clients'), 'item');
            }

            if ($method ==='getCardNames'){
                return array_get(array_get($response,'Items'),'item');
            }

            if( $method === 'updateCardInfo' ){

            }

            if($method === 'getClientCards'){
                return array_get(array_get($response,'Items'),'item');
            }

            if($method === 'pushSavePoints'){

            }

            return $response;
        }else{
            //todo prepare file that isn't pushed to the pos.
            if($method === 'addOrUpdateClient'){
                write_import_logs('15636-pos-addMember',$arguments,false, 'Unable to add/update member ');
            }else if ($method === 'updateCardInfo'){
                write_import_logs('15636-pos-updateCard',$arguments,false, 'Unable to add/update member ');
            }else if($method === 'pushSavePoints'){
                write_import_logs('15636-point-push-error',$arguments,false, 'Unable to memberpoint ');

            }
            write_import_logs('15636-CommonLogs',$arguments,false, (is_array($response))? json_encode($response) : $method);
            \Log::info($response);
//            throw new ApiException(null,['error' => array_get($response,'ReturnMessage','Something wrong with POS server')]);
        }
    }

    public function makeXmlForRequest($urn, $xsiType, $requestXml){
        $appToken = \Config::get('global.UNTILL_APP_TOKEN');
        $appName = \Config::get('global.UNTILL_APP_NAME');
        $xml = '<soapenv:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:TPAPIPosIntfU-ITPAPIPOS">
           <soapenv:Header/>
           <soapenv:Body>
              <urn:'.$urn.' soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
                 <Request xsi:type="'.$xsiType.'" xmlns:urn="urn:TPAPIPosIntfU">
                    <AppToken xsi:type="xsd:string">'.$appToken.'</AppToken>
                    <AppName xsi:type="xsd:string">'.$appName.'</AppName>
                    <Password xsi:type="xsd:string">' . \App\Services\Untill\UntillService::TEMP_PASSWORD . '</Password>
                    <UserName xsi:type="xsd:string">' . \App\Services\Untill\UntillService::TEMP_USERNAME . '</UserName>
                    '.$requestXml.'
                 </Request>
              </urn:'.$urn.'>
           </soapenv:Body>
        </soapenv:Envelope>';
        return $xml;
    }
    /**
     * @return mixed|null|\SimpleXMLElement|array
     * @throws ApiException
     */
    private function getClients()
    {
        return $this->client->call($this->makeXmlForRequest(self::URN['GET_CLIENTS'], self::XSI_TYPE['GET_CLIENTS'],''));
    }

    private function getInActiveClients()
    {
        $xml = '<Extra xsi:type="urn1:TExtraInfoArray" soapenc:arrayType="urn1:TExtraInfo[]" xmlns:urn1="urn:TPAPIPosTypesU">
                    <item xsi:type="NS3:TExtraInfo">
                  <Key xsi:type="xsd:string">is_active</Key>
                  <Value xsi:type="xsd:boolean">0</Value>
               </item>
                </Extra>';
        return $this->client->call($this->makeXmlForRequest(self::URN['GET_CLIENT_EX'], self::XSI_TYPE['GET_CLIENT_EX'],$xml));
    }

    /**
     * @param array $data
     * @return mixed|null|\SimpleXMLElement
     * @throws ApiException
     */
    private function addOrUpdateClient($data = [])
    {
        $expiryDate =  (array_get($data,'renewal')) ? date('Ymd', strtotime(array_get($data,'renewal'))): null ;
        $xml = '<Clients xsi:type="urn1:TClientsArray" soapenc:arrayType="urn1:TClient[0]">
                    <item xsi:type="NS3:TClient">
                        <Id xsi:type="xsd:long">' . array_get($data, 'untill_id', 0) . '</Id>
                        <Number xsi:type="xsd:int">' . array_get($data, 'member_id') . '</Number>                          
                        <Name xsi:type="xsd:string">' . array_get($data, 'full_name') . '</Name>
                        <Phone xsi:type="xsd:string">' . array_get($data, 'contact_no') . '</Phone>
                        <Email xsi:type="xsd:string">' . array_get($data, 'email') . '</Email>
                        <Address xsi:type="xsd:string">' . array_get($data, 'address') . '</Address>
                        ';
                        if(array_get($data,'date_of_birth')){
                            $birthdayValue = (array_get($data, 'date_of_birth')) ? array_get($data, 'date_of_birth'): null;
                            $xml .= '<BirthDate xsi:type="xsd:dateTime">' . $birthdayValue . '</BirthDate>';
                        }
                        $xml.='<CardNumber xsi:type="xsd:dateTime">' . base64_encode(array_get($data, 'swipe_card') ). '</CardNumber>
                        <Extra xsi:type="urn1:TExtraInfoArray" soapenc:arrayType="NS3:TExtraInfo[0]">
                            <item xsi:type="NS3:TExtraInfo">
                                <Key xsi:type="xsd:string">expiry_date</Key>
                                <Value xsi:type="xsd:string">'.$expiryDate.'</Value>
                                <Extra xsi:type="SOAP-ENC:Array" soapenc:arrayType="NS3:TExtraInfo[0]"/>
                            </item>
                        </Extra>
                    </item>
                </Clients>';
        $xml = $this->makeXmlForRequest(self::URN['UPDATE_CLIENTS'], self::XSI_TYPE['UPDATE_CLIENTS'], $xml);
        return $this->client->call($xml);
    }

    private function getClientCards($clientId) {
        $xml = '<ClientId xsi:type="xsd:long">'.$clientId.'</ClientId>';
       return $this->client->call($this->makeXmlForRequest(self::URN['GET_CLIENT_CARDS'],self::XSI_TYPE['GET_CLIENT_CARDS'],$xml));
    }

    private function getCardNames(){
        return $this->client->call($this->makeXmlForRequest(self::URN['GET_CLIENT_CARD_NAMES'],self::XSI_TYPE['GET_CLIENT_CARD_NAMES'],''));
    }

    private function updateCardInfo($clientId, $cardNameId, $cardNumber , $cardUntillId = null){
        $xml = '<Cards soapenc:arrayType="urn1:TClientCardInfo[1]" xsi:type="urn1:TOrderItemArray" xmlns:urn1="urn:TPAPIPosTypesU">
                    <item xsi:type="urn:TClientCardInfo">
                      ';
                        if($cardUntillId){
                            $xml .= '<Id xsi:type="xsd:long">'.$cardUntillId.'</Id>';
                        }
                      $xml .= '<Number xsi:type="xsd:int">1</Number>
                      <ClientId xsi:type="xsd:long">'.$clientId.'</ClientId>
                      <CardNameId xsi:type="xsd:long">'.$cardNameId.'</CardNameId>
                      <Card xsi:type="xsd:string">'.base64_encode($cardNumber).'</Card>
                      <IsActive xsi:type="xsd:boolean">true</IsActive>
                      <Extra xsi:type="urn1:TExtraInfoArray" soapenc:arrayType="urn1:TExtraInfo[]" xmlns:urn1="urn:TPAPIPosTypesU"/>
                   </item>
                </Cards>';
        return $this->client->call($this->makeXmlForRequest(self::URN['UPDATE_CLIENT_CARDS'],self::XSI_TYPE['UPDATE_CLIENT_CARDS'],$xml));
    }

    private function getClientById($untilId)
    {
        $xml = '<Extra xsi:type="urn1:TExtraInfoArray" soapenc:arrayType="urn1:TExtraInfo[]" xmlns:urn1="urn:TPAPIPosTypesU">
        		<item xsi:type="NS3:TExtraInfo">
                    <Key xsi:type="xsd:string">id</Key>
                    <Value xsi:type="xsd:string">'.$untilId.'</Value>
                </item>
            </Extra>';
        return $this->client->call($this->makeXmlForRequest(self::URN['GET_CLIENT_BY_ID'],self::XSI_TYPE['GET_CLIENT_BY_ID'],$xml));
    }

    private function pushSavePoints($clientId, $points)
    {
        $xml = '     <ClientId xsi:type="xsd:long">'.$clientId.'</ClientId>
            <Quantity xsi:type="xsd:int">'.round($points).'</Quantity>';
        return $this->client->call($this->makeXmlForRequest(self::URN['ADD_CLIENT_SAVE_POINT'],self::XSI_TYPE['ADD_CLIENT_SAVE_POINT'],$xml));
    }
}
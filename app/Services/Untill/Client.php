<?php
namespace App\Services\Untill;
use App\base\IResponseCode;
use App\Exceptions\ApiException;

/**
 * Created by PhpStorm.
 * User: Faisal
 * Date: 12/14/2019
 * Time: 10:16 AM
 */
class Client {
    private $password;
    private $username;
    private $soapUrl;
    private $headers;

    public function __construct($username,$password,$baseUrl,$port)
    {
        $this->setHeaders();
        $this->username = $username;
        $this->password = $password;
        $this->soapUrl = $baseUrl. ':' . $port. '/soap/ITPAPIPOS';
    }

    public function setHeaders($headers = [])
    {
        $this->headers = array(
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
        );
    }

    public function addCredentials($xml)
    {
        $xml = str_replace(UntillService::TEMP_USERNAME,$this->username,$xml);
        $xml = str_replace(UntillService::TEMP_PASSWORD,$this->password,$xml);
        return $xml;
    }
    public function call($xml){
        try{
            $response = null;
            $xml = $this->addCredentials($xml);
            $soap_do = curl_init();
            curl_setopt($soap_do, CURLOPT_URL, $this->soapUrl);
            curl_setopt($soap_do, CURLOPT_HEADER, false);
//            curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 100);
            curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT_MS , 10000);
            curl_setopt($soap_do, CURLOPT_TIMEOUT, 10);
            curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($soap_do, CURLOPT_POST, true);
            curl_setopt($soap_do, CURLOPT_POSTFIELDS, $xml);
            curl_setopt($soap_do, CURLOPT_HTTPHEADER, $this->headers);
            $result = curl_exec($soap_do);
            if ( $result  == false) {
                $err = 'Curl error: ' . curl_error($soap_do);
                curl_close($soap_do);
                \Log::error('Invalid pos Configuration. Username: '. $this->username . 'Url: ' . $this->soapUrl );
            } else {
                try{
                    curl_close($soap_do);
                    $clean_xml = str_ireplace(['SOAP-ENV:', 'SOAP:'], '', (string)$result);
                    $response = simplexml_load_string($clean_xml);
                    $response = $response->xpath('//return')[0];
                    $response = json_decode(json_encode($response),true);
                }catch (\Exception $exception){
                    \Log::info($result);
                }
            }
        }catch (\Exception $exception){
            \Log::error('Invalid pos Configuration. Username: '. $this->username . 'Url: ' . $this->soapUrl );
//            throw new ApiException(null,['error' => 'Invalid POS configurations.'],'Invalid Parameters for POS',IResponseCode::INTERNAL_SERVER_ERROR);
        }
        return $response;
    }
}

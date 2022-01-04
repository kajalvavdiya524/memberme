<?php
/**
 * Created by PhpStorm.
 * User: Faisal
 * Date: 5/5/2018
 * Time: 12:35 PM
 */

namespace App\Exceptions;


use App\base\IResponseCode;

class ApiException extends  \Exception
{
    public $data;
    public $errors;
    public $message;
    public $pendingOrg;
    public $responseCode;

    public function __construct($data = [], $errors = null, $message = null, $responseCode = 200,$pending_org = \App\base\IStatus::ACTIVE)
    {
        $this->data  = $data;
        $this->errors = $errors;
        $this->message = $message;
        $this->pendingOrg = $pending_org;
        $this->responseCode = $responseCode;

        $result = \App\Helpers\ApiHelper::apiResponse($data,$errors,$message);
        if($pending_org == \App\base\IStatus::INACTIVE){
            unset($result['pending_org']);
        }
        if(!empty($this->errors) && $responseCode == 200){
            $responseCode = IResponseCode::INTERNAL_SERVER_ERROR;
        }
        return response()->json($result, $responseCode);
    }
}
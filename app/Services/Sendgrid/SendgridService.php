<?php
/**
 * Created by PhpStorm.
 * User: Faisal
 * Date: 11/11/2019
 * Time: 6:43 PM
 */
namespace App\Services\Sendgrid;

use App\Exceptions\ApiException;
use App\Exceptions\Sendgrid\CustomSendgridException;
use App\Member;
use App\Organization;
use App\SendgridSetting;
use App\Services\Sendgrid\Lib\SendgridEmail;
use SendGrid\Mail\EmailAddress;
use SendGrid\Mail\Mail;

class SendgridService
{

    const SUCCESS_CODES = [
        200,202
    ];

    /**
     * @var $sendgrid \SendGrid
     */
    public $sendgrid;

    public function __construct()
    {

    }

    public function setup(Organization $organization,SendgridSetting $sendgridSetting = null)
    {
        if(empty($sendgridSetting)){
            /** @var SendgridSetting $sendgridSetting  sendgrid setting having sendgrid api key.*/
            $sendgridSetting =  $organization->sendgridSetting;
        }

        /*if(empty($sendgridSetting)){
            $sendgridSetting = SendgridSetting::whereOrganizationId(15550)->first();    //getting default sendgrid setting. should be removed when stable.
        }*/
        if(!empty($sendgridSetting)){
            $this->sendgrid = new \SendGrid($sendgridSetting->api_key);
        }else{
            throw new ApiException(null,['error' => 'Sendgrid Api isn\'t setup']);
        }
    }

    /**
     * @param $toEmails []
     * @param $parameters []  [ 'first_name' => 'Faisal', 'last_name' => 'Arif' , 'email' => 'faisaldeveloper7@gmail.com']
     * @param null $from
     * @return Mail
     * @throws \SendGrid\Mail\TypeException
     */
    public function setupMail($toEmails, $parameters, $from = null)
    {
        $email = new Mail();
        if(is_array($toEmails)){
            $email->addTos($toEmails);
        }else{
            $email->addTo($toEmails);
        }
        if(!empty($from) && filter_var($from, FILTER_VALIDATE_EMAIL)){
            $email->setFrom($from);
        }else{
            $email->setFrom('team@memberme.me');
        }

        foreach ($parameters as $key => $value) {
            try {
                $email->addDynamicTemplateData($key, $value);
            }catch (\Exception $exception){

            }
        }
        return $email;
    }

    public function addEmailWithSubstitutions(EmailAddress $emailAddress)
    {
        $mail = new Mail();
        $mail->addTo($emailAddress->getEmail(),$emailAddress->getName(),$emailAddress->getSubstitutions());
        $mail->setTemplateId('d-96da0ca102f1487da0990e4366f3426e');
        $mail->setFrom('team@memberme.me','Team');
        return $mail;
    }

    /**
     * @param $members Member
     * @param array $toEmails
     * @return array
     */
    public function composeTosByMembers($members , $toEmails = [])
    {
        $sendgridEmail = new SendgridEmail($toEmails);
        foreach ($members as $member) {
            $sendgridEmail->addEmail(array_get($member,'email'), array_get($members,'full_name'));
        }

        return $sendgridEmail->getEmails();
    }

    /**
     * This function will add one email into a new or an existing emails
     * @param $email
     * @param string $name
     * @param array $toEmails
     * @return array
     * @internal param SendgridEmail|null $sendgridEmail
     */
    public function composeTos($email, $name = ' ', $toEmails = [])
    {
        $sendgridEmail = new SendgridEmail($toEmails);
        $sendgridEmail->addEmail($email, $name);
        return $sendgridEmail->getEmails();
    }

    /**
     * will take an array of [ 'email' => 'faisaldeveloper7@gmail.com' , 'name' => 'first_name' ] and form out a sendgrid Tos object
     * @param $newToEmails
     * @param array $oldToEmails
     * @return mixed
     * @internal param $toEmails
     * @internal param SendgridEmail|null $sendgridEmail
     */
    public function composeMultipleTos($newToEmails, $oldToEmails = [])
    {
        $sendgridEmail = new SendgridEmail($oldToEmails);

        foreach ($newToEmails as $toEmail) {
            if ( isset($toEmail['email']) && isset($toEmail['name'])){
                $sendgridEmail->addEmail($toEmail['email'], $toEmail['name']);
            }
        }
        return $sendgridEmail->getEmails();
    }
    /**
     * @param Mail $mail
     * @param $templateId
     * @return Mail
     */
    public function setTemplateId(Mail $mail, $templateId)
    {
        $mail->setTemplateId($templateId);
        return $mail;
    }

    /**
     * @param $method
     * @param $arguments
     * @return bool|mixed
     * @throws CustomSendgridException
     */
    public function __call($method, $arguments)
    {
        if (method_exists($this, $method)) {    //calling all api call methods via this magic function to handle every type of response.
            $response = call_user_func_array(array($this, $method), $arguments);

            $responseBody = json_decode($response->body());
            if( !in_array($response->statusCode(), SendgridService::SUCCESS_CODES)  ) {

                if($response->statusCode() == 401 ) {
                    throw new CustomSendgridException('Unauthorised, Invalid Sendgrid api key');
                }

                if(!empty($responseBody->errors)){
                    $errors = (array)$errors =$responseBody->errors;
                    if(isset($errors[0])){
                        switch ($errors[0]->field){
                            case 'content':
                                throw new CustomSendgridException($errors[0]->message,CustomSendgridException::CODES['INVALID_CONTENT']);
                                break;
                            case 'subject':
                                throw new CustomSendgridException($errors[0]->message,CustomSendgridException::CODES['INVALID_SUBJECT']);
                                break;
                            case 'template_id':
                                throw new CustomSendgridException($errors[0]->message,CustomSendgridException::CODES['INVALID_TEMPLATE']);
                                break;
                            default:
                                \Log::info($errors[0]->message);
                                throw new CustomSendgridException($errors[0]->message,CustomSendgridException::CODES['INVALID_SETTING']);
                        }
                    }
                }else if( !empty($responseBody->error) ) {
                    throw new CustomSendgridException($responseBody->error,CustomSendgridException::CODES['NOT_FOUND']);
                }
                \Log::info($response->body());
                return false;
            }else{
                return (array)$responseBody;
            }
        }else{
            \Log::info('Invalid function Call');
        }
    }


    /**
     * @param Mail $mail
     * @return mixed
     */
    private function send(Mail $mail){      //This is a private function that will be called publically via __call() to handle response
        return $this->sendgrid->send($mail);
    }

    /**
     * @param $templateId
     * @return mixed
     */
    private function checkTemplateId($templateId){
        return $this->sendgrid->client->templates()->_($templateId)->get();
    }
}
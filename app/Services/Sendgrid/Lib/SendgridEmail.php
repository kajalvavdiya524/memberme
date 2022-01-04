<?php
/**
 * Created by PhpStorm.
 * User: Faisal
 * Date: 11/13/2019
 * Time: 12:49 PM
 */
namespace App\Services\Sendgrid\Lib;

/**
 * Class SendgridEmail
 * @package App\Services\Sendgrid\Lib
 */
class SendgridEmail
{
    /** @var  [] $emails */
    private $emails = [];

    public function __construct($emails = [])
    {
        $this->emails = $emails;
    }

    /**
     * @param $email
     * @param $name
     */
    public function addEmail($email, $name)
    {
        $this->emails[$email] = $name;
    }

    /**
     * @return array
     */
    public function getEmails()
    {
        return $this->emails;
    }
}
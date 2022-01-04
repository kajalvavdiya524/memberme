<?php
/**
 * Created by PhpStorm.
 * User: Faisal
 * Date: 11/11/2019
 * Time: 6:43 PM
 */
namespace App\Exceptions\Sendgrid;

use Throwable;

class CustomSendgridException extends \Exception {
    const CODES = [
        'INVALID_TEMPLATE' => 1,
        'INVALID_SUBJECT' => 2,
        'INVALID_EMAIL' => 3,
        'INVALID_CONTENT' => 4,
        'INVALID_ATTACHMENT' => 5,
        'INVALID_SETTING' => 6,
        'NOT_FOUND' => 7,
    ];

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code);
    }
}
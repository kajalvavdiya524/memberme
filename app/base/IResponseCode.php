<?php
/**
 * Created by PhpStorm.
 * User: Faisal
 * Date: 11/29/2017
 * Time: 10:16 PM
 */

namespace App\base;


interface IResponseCode
{
    const SUCCESS = 200;
    const SUCCESSFULLY_CREATED = 202;

    const USER_NOT_LOGGED_IN = 401;
    const NOT_ENOUGH_PERMISSIONS = 403;
    const NOT_FOUND = 404;
    const PRECONDITION_FAILED = 412;
    const INVALID_PARAMS = 422;

    const INTERNAL_SERVER_ERROR = 500;
}
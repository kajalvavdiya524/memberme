<?php
/**
 * Created by PhpStorm.
 * User: Faisal
 * Date: 2/10/2018
 * Time: 9:19 PM
 */

namespace App\Http;


use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
{
    public function response(array $errors)
    {
        return api_reponse(null,$errors);
    }
}
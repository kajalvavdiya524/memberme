<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class SaveUserDetails extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = self::validationRules();
        return $rules;
    }

    public static function requestValidate($request)
    {
        $rules = self::validationRules();
        $validator = Validator::make($request,$rules);
        return $validator;
    }

    public static function validationRules()
    {
        return [
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'email' => 'email',
            'phone' => 'numeric',
        ];
    }
}

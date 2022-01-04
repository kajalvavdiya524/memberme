<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class SaveDetailsRequest extends FormRequest
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
        return self::validationRules();
    }

    /**
     * return Validator
     * @param Request $request
     * @return mixed | Validator
     */
    public static function validateRequest($request)
    {
        $rules = self::validationRules();
        $validator = Validator::make($request,$rules);
        return $validator;
    }

    /**
     * return array of rules for this request
     *
     * @return array
     */
    public static function validationRules()
    {
        return [
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'contact_no' => 'required|numeric',

//            organization rules
            'organization_id' => 'required|exists:organizations,id',
            'name' => 'required',
            'contact_name' => 'required|min:3',
            'contact_phone' => 'required|numeric',
            'industry' => 'required',
            'physical_country' => 'required|numeric',
            'physical_first_address' => 'required',
            'physical_suburb' => 'required',
            'physical_city' => 'required',
            'physical_region' => 'required',
            'physical_postal_code' => 'required',

            /*'next_member' => 'required|numeric',
            'starting_receipt' => 'required|numeric',
            'starting_member' => 'required|numeric',*/
        ];
    }
}

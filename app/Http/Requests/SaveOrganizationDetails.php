<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;

class SaveOrganizationDetails extends FormRequest
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

    public static function validationRules()
    {
        return [
            'organization_id' => 'exists:organizations,id',
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
            'next_member' => 'required|numeric',
            'starting_receipt' => 'required|numeric',
            'starting_member' => 'required|numeric',
            'tax_rate' => 'numeric'

        ];
    }

    /**
     * @param $request
     * @return mixed
     */
    public static function requestValidate($request)
    {
        $rules = self::validationRules();
        $validator = Validator::make($request,$rules);
        return $validator;
    }

}

<?php

namespace App\Http\Requests;

use App\base\IResponseCode;
use App\Helpers\ApiHelper;
use App\Http\Request;
use Dotenv\Exception\ValidationException;

class CreateGroupRequest extends Request
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
        return [
            'organization_id' => 'required|exists:organizations:id'
        ];
    }
}
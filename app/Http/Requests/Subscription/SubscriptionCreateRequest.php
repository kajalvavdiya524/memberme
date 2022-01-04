<?php

namespace App\Http\Requests\Subscription;

use App\Subscription;
use Illuminate\Foundation\Http\FormRequest;

class SubscriptionCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return self::getRules();
    }

    public static function getRules()
    {
        return [
            'organization_id' => 'required|exists:organizations,id',
            'title' => 'required',
            'joining_fee' => 'required|numeric',
            'subscription_fee' => 'required|numeric',
            'expiry_date_option' => 'in:'.implode (",", array_values(Subscription::EXPIRY_DATE_OPTION)),
        ];
    }
}

<?php

namespace App\Http\Requests\Subscription;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionUpdateRequest extends FormRequest
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
            'id' => 'required|exists:subscriptions,id',
            'title' => 'required',
            'joining_fee' => 'required|numeric',
            'subscription_fee' => 'required|numeric',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserInvestmentRequest extends FormRequest
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
            'investment_id' =>  ['required', 'integer', 'exists:investments,id'],
            'units' =>  ['required', 'integer'],
            'payment_reference' => ['required', 'unique:user_investments,payment_reference', 'string'],
            'amount' => ['required', 'numeric'],
        ];
    }

    public function messages()
    {
        return [
            'payment_reference.unique' => 'The payment reference must be unique',
        ];
    }
}

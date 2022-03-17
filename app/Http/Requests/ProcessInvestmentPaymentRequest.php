<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessInvestmentPaymentRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'investment_id' =>  ['required', 'integer', 'exists:user_investments,id'],
            'payment_reference' => ['required', 'string'],
            'amount' => ['required', 'numeric'],
        ];
    }
}

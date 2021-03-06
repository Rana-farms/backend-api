<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateWithdrawalRequest extends FormRequest
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
            'amount' => ['required', 'numeric', 'min:50000'],
            'password' => ['required', 'string', 'min:6']
        ];
    }

    public function messages()
    {
        return [
            'amount.min' => 'The minimum amount to withdraw is 50,000',
        ];
    }
}

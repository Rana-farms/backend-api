<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class AddUserBankRequest extends FormRequest
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
            'bank_id' =>  ['required', 'integer'],
            'account_name' =>  ['required', 'string', 'max:255'],
            'account_no' =>  ['required', 'digits:10'],
        ];
    }
}

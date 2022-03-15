<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'fullname' =>  [ 'required', 'string', 'max:255'],
            'address' =>  ['required', 'string', 'max:255'],
            'phone' => ['required', 'unique:users,phone', 'numeric'],
            'email' =>  ['required', 'email', 'max:255', 'unique:users,email'],
            'password' =>  ['required', 'string', 'max:255', 'min:6'],
            'next_of_kin_fullname' =>  [ 'required', 'string', 'max:255'],
            'next_of_kin_address' =>  ['required', 'string', 'max:255'],
            'next_of_kin_phone' => ['required', 'numeric'],
            'relationship' =>  ['required', 'string', 'max:255'],
            'bank_id' =>  ['required', 'integer', 'exists:banks,id'],
            'account_name' =>  ['required', 'string', 'max:255'],
            'account_no' =>  ['required', 'digits:10'],
        ];
    }
}

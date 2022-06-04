<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InviteAdminRequest extends FormRequest
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
            'phone' => ['nullable', 'unique:users,phone', 'numeric'],
            'email' =>  ['required', 'email', 'max:255', 'unique:users,email'],
        ];
    }
}

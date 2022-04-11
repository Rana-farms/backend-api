<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'token' => 'required|string',
            'password' =>  ['required', 'string', 'max:255', 'min:6', 'confirmed'],
        ];
    }
}

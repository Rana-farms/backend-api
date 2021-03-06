<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NextOfKinRequest extends FormRequest
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
            'phone' => ['required', 'digits:11'],
            'relationship' =>  ['required', 'string', 'max:255'],
        ];
    }
}

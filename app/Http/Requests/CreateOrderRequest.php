<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' =>  [ 'required', 'string', 'max:255'],
            'weight' =>  ['required', 'integer'],
            'location' =>  ['required', 'string', 'max:255'],
            'aggregated' =>  ['nullable', 'string', 'max:255'],
            'weight_received' =>  ['nullable', 'integer', 'max:' . $this->weight],
            'weight_loss' =>  ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages()
    {
        return [
            'weight_received.max' => 'The value for weight received can not be greater than that of the weight field',
        ];
    }
}

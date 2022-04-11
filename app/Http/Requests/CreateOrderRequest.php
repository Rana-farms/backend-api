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
            'weight' =>  ['required', 'string', 'max:255'],
            'location' =>  ['required', 'string', 'max:255'],
            'aggregated' =>  ['required', 'string', 'max:255'],
            'weight_received' =>  ['required', 'string', 'max:255'],
            'weight_loss' =>  ['required', 'string', 'max:255'],
        ];
    }
}

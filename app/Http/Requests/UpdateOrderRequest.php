<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'weight' =>  ['required', 'string', 'max:255'],
            'location' =>  ['required', 'string', 'max:255'],
            'aggregated' =>  ['required', 'string', 'max:255'],
            'weight_received' =>  ['required', 'string', 'max:255'],
            'weight_loss' =>  ['required', 'string', 'max:255'],
            'order_status' => ['required', 'string', Rule::in(['Pending', 'Approved']) ],
            'aggregation_status' => ['required', 'string', Rule::in(['Pending', 'Initiated', 'Approved']) ],
            'negotiation_status' => ['required', 'string', Rule::in(['Pending', 'Initiated', 'Approved']) ],
            'delivery_status' => ['required', 'string', Rule::in(['Pending', 'Initiated', 'Approved']) ],
            'payment_status' => ['required', 'string', Rule::in(['Pending', 'Initiated', 'Approved']) ],
            'produce_loading' => ['required', 'string', Rule::in(['Pending', 'Initiated', 'Approved']) ],
        ];
    }
}

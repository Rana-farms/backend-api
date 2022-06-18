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
            'weight' =>  ['required', 'integer'],
            'location' =>  ['required', 'string', 'max:255'],
            'aggregated' =>  ['required', 'string', 'max:255'],
            'weight_received' =>  ['required', 'integer', 'max:' . $this->weight],
            'weight_loss' =>  ['nullable', 'string', 'max:255'],
            'order_status' => ['required', 'string', Rule::in(['Pending', 'Approved']) ],
            'aggregation_status' => ['required', 'string', Rule::in(['Pending', 'Initiated', 'Completed']) ],
            'negotiation_status' => ['required', 'string', Rule::in(['Pending', 'Initiated', 'Completed']) ],
            'delivery_status' => ['required', 'string', Rule::in(['Pending', 'Initiated', 'Completed']) ],
            'payment_status' => ['required', 'string', Rule::in(['Pending', 'Initiated', 'Completed']) ],
            'produce_loading' => ['required', 'string', Rule::in(['Pending', 'Initiated', 'Completed']) ],
        ];
    }

    public function messages()
    {
        return [
            'weight_received.max' => 'The value for weight received can not be greater than that of the weight field',
        ];
    }
}

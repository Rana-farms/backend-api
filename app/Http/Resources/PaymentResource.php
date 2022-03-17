<?php

namespace App\Http\Resources;
use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'paymentReference' => $this->payment_reference,
            'amount' => $this->amount,
            'status' => $this->is_active,
            'date' => Carbon::parse( $this->created_at )->format('M d Y H:i:s'),
        ];
    }
}

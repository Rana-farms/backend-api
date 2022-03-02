<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BankResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->bank_name,
            'abbreviation' => $this->abbreviation,
            'code' => $this->paystack_code,
        ];
    }
}

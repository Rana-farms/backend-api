<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserBankResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'bankId' => $this->bank_id,
            'bankName' => $this->bank->bank_name,
            'accountName' => $this->account_name,
            'accountNumber' => $this->account_no,
            'status' => $this->isActive,
        ];
    }
}

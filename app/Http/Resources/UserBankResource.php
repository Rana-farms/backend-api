<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserBankResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'bankId' => $this->bankId,
            'bankName' => $this->bankName,
            'accountName' => $this->account_name,
            'accountNumber' => $this->account_no,
            'status' => $this->isActive,
        ];
    }
}

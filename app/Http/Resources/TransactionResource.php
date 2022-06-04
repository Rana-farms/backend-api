<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "id" =>  $this->id,
            "user_id" =>  $this->user_id,
            "user" => new UserResource( $this->whenLoaded('user') ),
            "transaction_type" => $this->transaction_type,
            "type_id" => $this->type_id,
            "amount" => $this->amount,
            "status" => $this->completed_status,
            "dateCreated" => $this->created_at,
        ];
    }
}

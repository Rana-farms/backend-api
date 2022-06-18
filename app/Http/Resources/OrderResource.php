<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'location' => $this->location,
            'weight' => $this->weight,
            'weightReceived' => $this->weight_received,
            'weightLoss' => $this->the_weight_loss,
            'aggregated' => $this->aggregated,
            'orderStatus' => $this->order_status,
            'aggregationStatus' => $this->aggregation_status,
            'negotiationStatus' => $this->negotiation_status,
            'deliveryStatus' => $this->delivery_status,
            'paymentStatus' => $this->payment_status,
            'produceLoading' => $this->produce_loading,
            'code' => $this->code,
            'dateCreated' => $this->created_at,
        ];
    }
}

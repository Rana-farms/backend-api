<?php

namespace App\Http\Resources;
use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class NextOfKinResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'fullName' => $this->fullname,
            'address' => $this->address,
            'phone' => $this->phone,
            'relationship' => $this->relationship,
            'dateCreated' => Carbon::parse( $this->created_at )->format('M d Y')
        ];
    }
}

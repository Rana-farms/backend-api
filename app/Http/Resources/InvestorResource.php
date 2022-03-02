<?php

namespace App\Http\Resources;
use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class InvestorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'userId' => $this->user_id,
            'fullName' => $this->fullname,
            'phone' => $this->phone,
            'address' => $this->address,
            'joinedAt' => Carbon::parse( $this->created_at )->format('M d Y')
        ];
    }
}

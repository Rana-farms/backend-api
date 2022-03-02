<?php

namespace App\Http\Resources;
use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class NextOfKinResource extends JsonResource
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
            'fullName' => $this->fullname,
            'address' => $this->address,
            'relationship' => $this->relationship,
            'dateCreated' => Carbon::parse( $this->created_at )->format('M d Y')
        ];
    }
}

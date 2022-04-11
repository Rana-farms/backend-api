<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    public function toArray($request)
    {
        return [ 
            'id' => $this->id,
            'email' => $this->email,
            'fullname' => $this->fullname,
            'address' => $this->address,
            'username' => $this->username,
            'phone' => $this->phone,
            'emailVerifiedStatus' => $this->is_verified,
            'status' => $this->isActive,
            'role' => new RoleResource( $this->role ),
            'nextOfKin' => new NextOfKinResource( $this->whenLoaded('nextOfKin') ),
            'bank' => new UserBankResource( $this->whenLoaded('bank') ),
            'wallet' => new WalletResource( $this->whenLoaded('wallet') ),
        ];
    }
}

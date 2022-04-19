<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    public function toArray($request)
    {
        if( $this->identity_document ){
            $identityDocument = url("public/images/{$this->identity_document}");
        } else{
            $identityDocument = '';
        }

        return [
            'id' => $this->id,
            'email' => $this->email,
            'fullname' => $this->fullname,
            'address' => $this->address,
            'username' => $this->username,
            'phone' => $this->phone,
            'identityDocument' => $identityDocument,
            'emailVerifiedStatus' => $this->email_verified,
            'isVerified' => $this->is_verified,
            'status' => $this->isActive,
            'role' => new RoleResource( $this->role ),
            'nextOfKin' => new NextOfKinResource( $this->whenLoaded('nextOfKin') ),
            'bank' => new UserBankResource( $this->whenLoaded('bank') ),
            'wallet' => new WalletResource( $this->whenLoaded('wallet') ),
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    public function toArray($request)
    {
        if ($this->identity_document) {
            $identityDocument = url("public/images/{$this->identity_document}");
        } else {
            $identityDocument = '';
        }

        $data = [
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
        ];

      if( $this->role_id == 9){
       $data['investmentStatus'] = $this->investment_status;
       $data['investmentTrust'] = $this->investment_trust;
       $data['totalInvestment'] = $this->total_investment;
       $data['totalReceived'] = $this->total_received;
       $data['currentInvestment'] = $this->current_investment;
       $data['nextOfKin'] = new NextOfKinResource( $this->whenLoaded('nextOfKin') );
       $data['bank'] = new UserBankResource( $this->whenLoaded('bank') );
       $data['wallet'] = new WalletResource( $this->whenLoaded('wallet') );
       $data['transactions'] = TransactionResource::collection( $this->whenLoaded('transactions') );
       $data['investments'] = UserInvestmentResource::collection( $this->whenLoaded('investments') );
        }

        return $data;
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    public function toArray($request)
    {
        $roleId = $this->role_id;
        if( $roleId === 1 ){
            $profile = new AdminResource( $this->profile );
        }

        if( $roleId === 9 ){
            $profile = new InvestorResource( $this->profile );
        }

        if( $roleId === 18 ){
            $profile = new EmployeeResource($this->profile);
        }


        return [
            'id' => $this->id,
            'email' => $this->email,
            'username' => $this->username,
            'status' => $this->isActive,
            'role' => new RoleResource( $this->role ),
            'profile' => $profile,
        ];
    }
}

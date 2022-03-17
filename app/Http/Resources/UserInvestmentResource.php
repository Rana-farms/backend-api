<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserInvestmentResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'investment_id' => $this->investment_id,
            'amount' => $this->amount,
            'startDate' => $this->start_date ? Carbon::parse($this->start_date)->format('M d Y') : '',
            'dueDate' => $this->end_date ? Carbon::parse($this->end_date)->format('M d Y') : '',
            'isPaid' => $this->is_paid,
            'status' => $this->is_active,
        ];
    }
}

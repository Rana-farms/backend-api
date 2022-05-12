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
            'userId' => $this->user_id,
            'investmentId' => $this->investment_id,
            'investmentName' => $this->investment ? $this->investment->name : '',
            'amount' => $this->amount,
            'startDate' => $this->start_date ? Carbon::parse($this->start_date)->format('M d Y') : '',
            'dueDate' => $this->end_date ? Carbon::parse($this->end_date)->format('M d Y') : '',
            'isPaid' => $this->is_paid,
            'status' => $this->is_active,
        ];
    }
}

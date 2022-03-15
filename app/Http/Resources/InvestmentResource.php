<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvestmentResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'trustee' => $this->trustee,
            'minimumUnit' => $this->minimum_unit,
            'unitPrice' => $this->unit_price,
            'lockUpPeriod' => $this->lock_up_period,
            'insuranceFee' => $this->insurance_fee,
            'description' => $this->description,
            'assetAllocation' => $this->asset_allocation,
            'profitSharingFormula' => $this->profit_sharing_formula,
            'riskProfiile' => $this->risk_profile,
        ];
    }
}

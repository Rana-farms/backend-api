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
            'totalUnits' => $this->total_units,
            'minimumUnit' => $this->minimum_unit,
            'unitsBought' => $this->units_bought,
            'unitsRemaining' => $this->units_remaining,
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

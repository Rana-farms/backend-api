<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'trustee',
        'total_units',
        'minimum_unit',
        'unit_price',
        'lock_up_period',
        'insurance_fee',
        'description',
        'asset_allocation',
        'profit_sharing_formula',
        'risk_profile',
        'status',
    ];

    protected $appends = ['units_bought', 'units_remaining'];

    public function getUnitsBoughtAttribute()
    {
        $userInvestments = UserInvestment::whereInvestmentId($this->id)->whereStatus(1)->get();
        if( $userInvestments){
            $units =  $userInvestments->sum('units');
        }
        return $userInvestments ? $units : 0;
    }

    public function getUnitsRemainingAttribute()
    {
        return $this->total_units - $this->units_bought;
    }
}

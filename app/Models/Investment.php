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

}

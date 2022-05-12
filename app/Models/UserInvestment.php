<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInvestment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'investment_id',
        'payment_reference',
        'units',
        'amount',
        'start_date',
        'end_date',
        'is_paid',
        'status',
    ];

    protected $appends = ['isActive', 'isPaid'];

    public function getisActiveAttribute(){

        if($this->status == 1){
            return 'active';
        }

        return 'inactive';
    }

    public function getisPaidAttribute(){

        if($this->status == 1){
            return 'paid';
        }

        return 'pending';
    }

    public function investment()
    {
        return $this->belongsTo(Investment::class);
    }


}

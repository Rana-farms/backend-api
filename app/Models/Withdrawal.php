<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

    CONST PROCESSING = 'Processing';
    CONST COMPLETED = 'Completed';
    CONST PENDING = 'Pending';

    protected $fillable = [
        'user_id',
        'amount',
        'status',
        'payment_reference',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bank()
    {
        return $this->belongsTo(UserBank::class, 'user_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'transaction_type',
        'type_id',
        'status',
    ];

    protected $appends = [
        'completed_status',
    ];

    public function getCompletedStatusAttribute(){
        return $this->status == 1 ? 'success' : 'pending';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

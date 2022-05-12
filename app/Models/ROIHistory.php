<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ROIHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'investment_id',
        'status',
    ];
}

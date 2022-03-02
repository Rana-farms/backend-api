<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NextOfKin extends Model
{
    use HasFactory;

    protected $fillable = [
        'fullname',
        'address',
        'phone',
        'user_id',
        'relationship',
    ];

    
}

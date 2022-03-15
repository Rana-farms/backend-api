<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'status',
    ];

    protected $appends = ['isApproved'];

    public function getisApprovedAttribute(){

        if($this->status == 1){
            return 'success';
        }

        return 'pending';
    }

}

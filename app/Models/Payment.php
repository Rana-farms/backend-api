<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'payment_reference',
        'code',
        'status',
    ];

    protected $appends = ['isActive'];

    public function getisActiveAttribute(){

        if($this->status === 1){
            return 'success';
        }
        return 'pending';
    }
    
    public static function boot() {

        parent::boot();

        self::creating(function ($model) {

            $model->code = Str::random(6);

        });
    }
}

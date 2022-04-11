<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'weight',
        'weight_received',
        'weight_loss',
        'aggregated',
        'order_status',
        'aggregation_status',
        'negotiation_status',
        'delivery_status',
        'payment_status',
        'produce_loading',
        'code',
    ];

    public static function boot() {

        parent::boot();

        self::creating(function ($model) {
            $code = random_int(1000, 9999);
            $model->code = $code;
        });
    }
}

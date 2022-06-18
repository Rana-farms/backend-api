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

    protected $appends = ['the_weight_loss'];

    public static function boot() {

        parent::boot();

        self::creating(function ($model) {
            $code = random_int(1000, 9999);
            $model->code = $code;
        });
    }

    public function getTheWeightLossAttribute(){
        $weightReceived = $this->weight_received;
        $weightAggregated = $this->weight;

        if(is_null($weightAggregated)){
            return 0;
        }

        if(is_null($weightReceived)){
            return 0;
        }

        $substract = $weightReceived - $weightAggregated;
        $percentage = $substract / $weightAggregated * 100;
        return $percentage;
    }
}

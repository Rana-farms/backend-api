<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'file',
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

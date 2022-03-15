<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'password',
        'status',
        'username',
        'fullname',
        'address',
        'phone',
        'profile_completed',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = ['isActive', 'is_verified' ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getisActiveAttribute(){

        if($this->status === 1){
            return 'active';
        }

        return 'inactive';
    }

    public function getIsVerifiedAttribute(){

        if( ! empty( $this->email_verified_at ) ){
            return 'verified';
        }
        return 'unverified';
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function nextOfKin()
    {
        return $this->hasOne(NextOfKin::class);
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function bank()
    {
        return $this->hasOne(UserBank::class);
    }

    public static function boot() {

        parent::boot();

        self::creating(function ($model) {

            $rand = random_int(100000, 999999);
            $model->username = 'RA'. $rand;

        });

    }
}

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
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = ['isActive'];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getisActiveAttribute(){

        if($this->status === 1){
            return 'active';
        }

        return 'inactive';
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function profile(){
        $userRole = $this->role_id;

        if( $userRole === 1){
            return $this->hasOne(Admin::class, 'user_id');
        }

        if( $userRole === 9){
            return $this->hasOne(Investor::class, 'user_id');
        }

        if( $userRole === 18 ){
            return $this->hasOne(Employee::class, 'user_id');
        }

        return '';
    }

    public static function boot() {

        parent::boot();

        self::creating(function ($model) {

            $rand = random_int(100000, 999999);
            $model->username = 'RA'. $rand;

        });

    }
}

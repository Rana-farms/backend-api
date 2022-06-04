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

    CONST SUPERADMINEMAIL = 'veecthorpaul@gmail.com';

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
        'identity_document',
        'verified',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'isActive',
        'is_verified',
        'email_verified',
        'investment_status',
        'investment_trust',
        'total_investment',
        'total_received',
        'current_investment',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getIsActiveAttribute(){
        return $this->status == 1 ? 'active' : 'inactive';
   }

    public function getIsVerifiedAttribute(){
        return $this->verified == 1 ? true : false;
    }

    public function getEmailVerifiedAttribute(){
        return ! empty( $this->email_verified_at ) ? 'verified' : 'unverified';
    }

    public function getInvestmentStatusAttribute(){
        $investment = UserInvestment::whereUserId(1)->whereStatus(1)->first();
        return $investment ? 'active' : 'inactive';
    }

    public function getInvestmentTrustAttribute(){
        $investment = UserInvestment::whereUserId(1)->whereStatus(1)->first();
        if($investment){
            $getInvestment = Investment::find($investment->investment_id);
            $investmentName = $getInvestment ? $getInvestment->name : 'Agricultural Commodity Trust';
        }
        return $investment ? $investmentName : 'null';
    }

    public function getTotalReceivedAttribute(){
        $roi = ROIHistory::whereUserId($this->id)->get();
        if($roi){
            $total = $roi->sum('amount');
        }
        return $roi ? $total : 0.00;
    }

    public function getCurrentInvestmentAttribute(){
        $investment = UserInvestment::whereUserId($this->id)->whereStatus(1)->latest()->first();
        return $investment ? $investment->amount : 0.00;
    }

    public function getTotalInvestmentAttribute(){
        $investment = UserInvestment::whereUserId($this->id)->get();
        if($investment){
            $total = $investment->sum('amount');
        }
        return $investment ? $total : 0.00;
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

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    public function investments()
    {
        return $this->hasMany(UserInvestment::class, 'user_id');
    }

    public static function boot() {

        parent::boot();

        self::creating(function ($model) {

            $rand = random_int(100000, 999999);
            $model->username = 'RA'. $rand;

        });

    }

}

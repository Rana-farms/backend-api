<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBank extends Model
{
    use HasFactory;
 
    protected $fillable = [
        'user_id',
        'bank_id',
        'account_name',
        'code',
        'account_number',
        'account_no',
    ];

    protected $appends = ['isActive', 'bankName'];

    public function getisActiveAttribute(){

        if($this->status == 1){
            return 'active';
        }

        return 'inactive';
    }

    public function getBankNameAttribute(){
         $bank = Bank::where('paystack_code', $this->code)->first();
         if( $bank ){

          return $bank->bank_name;
          } else{
              return '';
          }
    }
}

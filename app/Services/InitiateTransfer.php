<?php

namespace App\Services;

use App\Services\FinalTransfer;



class InitiateTransfer
{

    protected $finalTransfer;

    public function __construct(FinalTransfer $finalTransfer){
        $this->finalTransfer = $finalTransfer;
    }

    /*
    *   Initiate transfer money to a recepient
    */
    public function execute($response, array $data)
    {
        $url = "https://api.paystack.co/transfer";
        $fields = [
          "source" => "balance", 
          "reason" => $data['reason'], 
          "amount" => $data['amount'], 
          "recipient" => $response['data']['recipient_code']
          ];
          
        $fields_string = http_build_query($fields);
        //open connection
        $ch = curl_init();
        
        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          "Authorization: Bearer ".('sk_test_17e8bb75cbab4ce27af543711f0cb71ad18f1677'),
          "Cache-Control: no-cache",
    ));
    
        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
        
        //execute post
        $result = curl_exec($ch);
        
        $response = json_decode($result, true);
         dd($response);
        if($response['status']  == false){
            return $response;
        }
        
        return $this->finalTransfer->execute($response);
    }

}
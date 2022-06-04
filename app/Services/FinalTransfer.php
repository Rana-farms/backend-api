<?php

namespace App\Services;


class FinalTransfer
{

    public function __construct(){

    }

    /*
    *   Final transfer after successful transfer initiation
    */
    public function execute($response)
    {
      $url = "https://api.paystack.co/transfer/finalize_transfer";
      $fields = [
        "transfer_code" => $response['data']['transfer_code'],
        "otp" => "928783" // Fake OTP
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
      
      return $response;
    }

}
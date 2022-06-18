<?php

namespace App\Services;


class TransferHistory
{

    public function __construct(){

    }

    /*
    *   List payment transfer history
    */
    public function execute()
    {
      $curl = curl_init();

      curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.paystack.co/transfer",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
          "Authorization: Bearer ".('sk_test_0153f88659f450aa4a6e2cede0c6af12735b2582'),
          "Cache-Control: no-cache",
        ),
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);
      curl_close($curl);


      if ($err) {
      return  "cURL Error #:" . $err;
       }

    return $response;
    }

}

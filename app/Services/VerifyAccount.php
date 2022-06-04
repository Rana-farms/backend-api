<?php

namespace App\Services;

use App\Services\TransferRecipient;


class VerifyAccount
{

    protected $transferRecipient;

    public function __construct(TransferRecipient $transferRecipient){
        $this->transferRecipient = $transferRecipient;

    }

    /*
    *   Verify the account number
    */
    public function execute(array $data)
    {
        $accountNumber = $data['account_number'];
        $bankCode = $data['bank_code'];

    	// Let's store the amount received from the customer
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.paystack.co/bank/resolve?account_number=".$accountNumber."&bank_code=$bankCode",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer ".'sk_test_17e8bb75cbab4ce27af543711f0cb71ad18f1677',
            "Cache-Control: no-cache",
            ),
        ));
        $response = curl_exec($curl);

        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return  "cURL Error #:" . $err;
        }

        $response = json_decode($response, true);



        return $this->transferRecipient->execute($response, $data);
    }

}

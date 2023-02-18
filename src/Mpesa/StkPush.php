<?php

namespace Denniskemboi\PaymentGateway\Mpesa;

use Dotenv\Dotenv;
use Exception;
use stdClass;

class StkPush extends App{
    protected $callback_url = "";
    protected $amount = 0.00;
    protected $phone = "";
    protected $reference = "";
    protected $description = "goods/servises payment";
    protected $transaction_type = "CustomerPayBillOnline";
    protected $initiate_url = "https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest";
    public function __construct()
    {
        // initialize dot env
        Dotenv::createImmutable(__DIR__. '/../..')->load();
    }

    public function setCallbackUrl($url){
        $this->callback_url = $url;
        return $this;
    }
    public function setAmount($amount){
        $this->amount = $amount;
        return $this;
    }
    public function setPhone($phone){
        $this->phone = $phone;
        return $this;
    }
    public function setReference($ref){
        $this->reference = $ref;
        return $this;
    }
    public function setRemarks($desc){
        $this->description = $desc;
        return $this;
    }
    public function setTransactionType($type){
        $this->transaction_type = $type;
        return $this;
    }

    // make buy goods stkpush
    public function tillRequestPush(){
        $stkheader = ['Content-Type:application/json','Authorization:Bearer '.$this->getLiveToken()->access_token];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->initiate_url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $stkheader);

        $timestamp = date('YmdHis');
    
        $curl_post_data = array(
            'BusinessShortCode' => $_ENV['MPESA_SHOT_CODE'],
            'Password' => $this->getPassword($timestamp),
            'Timestamp' => $timestamp,
            'TransactionType' => $this->transaction_type,
            'Amount' => $this->amount,
            'PartyA' => $this->phone,
            'PartyB' => $_ENV['MPESA_SHOT_CODE'],
            'PhoneNumber' => $this->phone,
            'CallBackURL' => $this->callback_url,
            'AccountReference' => $this->reference,
            'TransactionDesc' => $this->description
        );
    
        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        $curl_response = curl_exec($curl);
        
        return json_decode($curl_response);
    }

}
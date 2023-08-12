<?php

namespace Techlup\PaymentGateway\Mpesa;

use Dotenv\Dotenv;
use Exception;
use stdClass;

class StkPush extends App
{
    protected $callback_url = "";
    protected $amount = 0.00;
    protected $phone = "";
    protected $reference = "";
    protected $description = "goods/servises payment";
    protected $transaction_type = "CustomerPayBillOnline";
    protected $initiate_url = '';
    public function __construct($root = __DIR__ . '/../..')
    {
        parent::__construct($root);
        // initialize dot env
        Dotenv::createImmutable($this->app_root)->load();
        $this->initiate_url='https://'.$this->isSandbox()?'sandbox':'api'.'.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
    }

    public function setCallbackUrl($url)
    {
        $this->callback_url = $url;
        return $this;
    }
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }
    public function setReference($ref)
    {
        $this->reference = $ref;
        return $this;
    }
    public function setRemarks($desc)
    {
        $this->description = $desc;
        return $this;
    }
    public function setTransactionType($type)
    {
        $this->transaction_type = $type;
        return $this;
    }

    // make buy goods stkpush
    public function tillRequestPush()
    {
        $stkheader = ['Content-Type:application/json', 'Authorization:Bearer ' . $this->getLiveToken()->access_token];
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

    // get api callback data
    public static function getCallbackData()
    {
        header("Content-type: application/json; charset=utf-8");
        date_default_timezone_set('Africa/Nairobi');
        $json = file_get_contents('php://input');

        $data = json_decode($json)->Body->stkCallback;

        $response = new stdClass();
        $response->status = $data->ResultCode;
        $response->MerchantRequestID = $data->MerchantRequestID;
        $response->CheckoutRequestID = $data->CheckoutRequestID;
        $response->ResultDesc = $data->ResultDesc;

        if (property_exists($data, "CallbackMetadata")) {
            $metadata = $data->CallbackMetadata->Item;
            foreach ($metadata as $val) {
                foreach ($val as $v) {
                    $name = $val->Name;
                    $response->$name = $v;
                }
            }
        }

        return $response;
    }
}

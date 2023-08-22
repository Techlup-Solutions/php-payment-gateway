<?php

require_once realpath(__DIR__ . '/vendor/autoload.php');
use Techlup\PaymentGateway\Mpesa\StkPush;

$data = new StkPush('./');
$data->setCallbackUrl("https://en9hu12xsn4f7.x.pipedream.net/") // required
->setAmount("10") // required
->setPhone("254724974848") // required 254*********
->setReference("test") //reuired * e.g account number, room number, etc
->setRemarks("testing api"); // optional

$response = $data->tillRequestPush();

print_r($response);
?>
<?php

require_once realpath(__DIR__ . '/vendor/autoload.php');

use Denniskemboi\PaymentGateway\Mpesa\StkPush;

$data = new StkPush();
$data->setCallbackUrl("https://encv2yd32vb2e.x.pipedream.net/") // required
    ->setAmount("1") // required
    ->setPhone("254724974848") // required
    ->setReference("House Rent") //reuired *
    ->setRemarks("your remarks"); // optional
echo json_encode($data->tillRequestPush());
echo "\n";
?>
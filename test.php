<?php

require_once realpath(__DIR__ . '/vendor/autoload.php');

use Techlup\PaymentGateway\Mpesa\B2C;

// you can use the code below to format user phone number
$phone = preg_replace('/^(?:\+?254|0)?/', '254', 'phone');

// when initializing stkpush, you will have to give the root folder
// this will help the class to find your env file
// laravel users use base_path() function

$data = B2C::getCallbackData();
echo json_encode($data);
?>
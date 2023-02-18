<?php

require_once realpath(__DIR__ . '/vendor/autoload.php');

use Denniskemboi\PaymentGateway\Mpesa\StkPush;
echo json_encode(StkPush::getCallbackData());
echo "\n";
?>
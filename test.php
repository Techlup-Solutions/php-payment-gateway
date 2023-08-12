<?php

require_once realpath(__DIR__ . '/vendor/autoload.php');

use Techlup\PaymentGateway\Mpesa\StkPush;
echo json_encode(StkPush::getCallbackData());
echo "\n";
?>
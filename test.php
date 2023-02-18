<?php

require_once realpath(__DIR__ . '/vendor/autoload.php');

use Denniskemboi\PaymentGateway\Mpesa\App;

$app = new App();
echo json_encode($app->getLiveToken());
echo "\n";
echo json_encode($app->getPassword());
echo "\n";
?>
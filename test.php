<?php

require_once realpath(__DIR__ . '/vendor/autoload.php');

$paypal_subscription = new Techlup\PaymentGateway\Paypal\Subscription();

$subscriber = array(
    'name' => array(
        'given_name' => 'John',
        'surname' => 'Doe'
    ),
    'email_address' => 'customer@example.com',
    'shipping_address' => array(
        'name' => array(
            'full_name' => 'John Doe'
        ),
        'address' => array(
            'address_line_1' => '',
            'address_line_2' => '',
            'admin_area_2' => '',
            'admin_area_1' => '',
            'postal_code' => '',
            'country_code' => 'US'
        )
    )
);

$plan = new stdClass();
$plan->id = 'P-9PP51961TH493415EMW4NX5A';
$plan->value = '18';

//print_r($paypal_subscription->subscribe(
//    $plan,
//    $subscriber,
//    'https://enx316omyr0k.x.pipedream.net/',
//    'https://enx316omyr0k.x.pipedream.net/'
//));
//print_r($paypal_subscription->plans());
print_r($paypal_subscription->cancelSubscription('I-25GFPAT3X2MT'));
?>
# Techlup Pay
## Php Payment Gateway

Makes it easy to intergrate payment gatway to your php project.

install using composer

> composer require techlup/payment-gateway

# M-Pesa intergration

## configuring the app

Add the following to the env, ass per your project, shotcode is the till number for the buy goods. Ensure your app is live on developer portal

```env
MPESA_CONSUMER_KEY=""
MPESA_CONSUMER_SECRET=""
MPESA_PASS_KEY=""
MPESA_SHOT_CODE=""
```

To switch from sandbox and live you can add the following to your env file

```env
TECHLUP_PAYMENT_SANDBOX=true
```
## make an stk push for buy goods

### initiating the request

first import the dependency to your php class, create an instance of stkpush, then make your request as shown in the code below.

```php


// import the dependency
use Techlup\PaymentGateway\Mpesa\StkPush;

// you can use the code below to format user phone number
preg_replace('/^(?:\+?254|0)?/', '254', $request->input("phone"));

// when initializing stkpush, you will have to give the root folder
// this will help the class to find your env file
// laravel users use base_path() function

$data = new StkPush(base_path());
$data->setCallbackUrl("{YOUR_API_CALLBACK}") // required
    ->setAmount("{AMOUNT}") // required
    ->setPhone("{PHONE_NUMBER}") // required 254*********
    ->setReference("{REF}") //reuired * e.g account number, room number, etc
    ->setRemarks("your remarks"); // optional

$response = $data->tillRequestPush();

```

after the request is submmited, the following response will be ruturned from mpesa.

<b>Keep in mind that $response is an object of std class</b>. bellow is an example on how you can access response properties.

to access MerchantRequestID for example `$response->MerchantRequestID`.

```json
{
  "MerchantRequestID": "119215-30497********",
  "CheckoutRequestID": "ws_CO_18022023203127******",
  "ResponseCode": "0",
  "ResponseDescription": "Success. Request accepted for processing",
  "CustomerMessage": "Success. Request accepted for processing"
}
```

### accessing the callback.

on every request you make, m-pesa will make a request to your api url you gave.

I have made it easy for you to access the callback data.

```php
// first import the dependencies
use Techlup\PaymentGateway\Mpesa\StkPush;

// get the callback data
$data = StkPush::getCallbackData();
```

below is the format of data returned, <b>dont forget that it is Std Class, don't confuse with array.</b>

```json
{
  "status": 0,
  "MerchantRequestID": "119215-30497********",
  "CheckoutRequestID": "ws_CO_1802202319494*******",
  "ResultDesc": "The service request is processed successfully.",
  "Amount": 1,
  "MpesaReceiptNumber": "RBI6******",
  "Balance": "Balance",
  "TransactionDate": 20230218194956,
  "PhoneNumber": "254724******"
}
```

# PayPal integration

First you need to configure your env by adding the following.
```dotenv
PAYPAL_CLIENT_ID=""
PAYPAL_SECRET=""
```
You can always switch between live and sandbox by updating `
TECHLUP_PAYMENT_SANDBOX=true` to `false`.

## Subscription

To subscribe users to your system using PayPal follow the steps below.

- import the dependencies
```php
use TTechlup\PaymentGateway\Paypal\Subscription;
```
- create the class instance
```php
$paypal_subscription = new Subscription(base_path()); // base_path() is the base dir of your application where .env is located;
```
- subscribe the user
```php
// define the subscriber
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

// specify your plan
// create the plan on paypal and the the plan id
$plan = new stdClass();
$plan->id = 'P-9PP51961TH493415EMW4NX5A';
$plan->value = '18';

// create the agreement
$agreement = $paypal_subscription->subscribe(
    $plan,
    $subscriber,
    'https://enx316omyr0k.x.pipedream.net/', // return url
    'https://enx316omyr0k.x.pipedream.net/' // cancel url
);

```
Sample response
```json

{
  "status": "APPROVAL_PENDING",
  "id": "I-25GFPAT3X2MT",
  "create_time": "2024-01-30T12:21:02Z",
  "links": [
    {
      "href": "https://www.sandbox.paypal.com/webapps/billing/subscriptions?ba_token=BA-0RL61114E6855090G",
      "rel": "approve",
      "method": "GET"
    },
    {
      "href": "https://api.sandbox.paypal.com/v1/billing/subscriptions/I-25GFPAT3X2MT",
      "rel": "edit",
      "method": "PATCH"
    },
    {
      "href": "https://api.sandbox.paypal.com/v1/billing/subscriptions/I-25GFPAT3X2MT",
      "rel": "self",
      "method": "GET"
    }
  ]
}
```

Now direct the user to the approval url. The user will be direct to the return url you provided

- check if subscription was success
```php
    $subscriptionDetails = $paypal_subscription->getSubscriptionDetails('SUBSCRIPTION_ID');
    if ($subscriptionDetails['status'] === 'ACTIVE') {
        // Subscription was successfully approved and activated
        // Update database with subscription status
        // For example, update the user's subscription status in the database
    } else {
        // Subscription approval failed or encountered an error
        // Handle the error
        // For example, update the database to mark the subscription as failed
        // And redirect the user to an error page
    }
```
- cancel subscription
```php
$paypal_subscription->cancelSubscription('SUBSCRIPTION_ID');
```
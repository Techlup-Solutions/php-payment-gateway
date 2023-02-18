## Php Payment Gateway

Makes it easy to intergrate payment gatway to your php project.

install using composer

> composer require denniskemboi/payment-gateway

# M-Pesa intergration

## configuring the app

Add the following to the env, ass per your project, shotcode is the till number for the buy goods. Ensure your app is live on developer portal

```env
MPESA_CONSUMER_KEY=""
MPESA_CONSUMER_SECRET=""
MPESA_PASS_KEY=""
MPESA_SHOT_CODE=""
```

## make an stk push for buy goods

### initiating the request

first import the dependency to your php class, create an instance of stkpush, then make your request as shown in the code below.

```php


// import the dependency
use Denniskemboi\PaymentGateway\Mpesa\StkPush;

$data = new StkPush();
$data->setCallbackUrl("{YOUR_API_CALLBACK}") // required
    ->setAmount("{AMOUNT}") // required
    ->setPhone("{PHONE_NUMBER}") // required 254*********
    ->setReference("{REF}") //reuired * e.g account number, room number, etc
    ->setRemarks("your remarks"); // optional

$response = $data->tillRequestPush();

```
after the request is submmited, the following response will be ruturned from mpesa.

<b>Keep in mind that $response is an object of std class</b>. bellow is an example on how you can access response properties.

to access MerchantRequestID for example `` $response->MerchantRequestID ``.

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
use Denniskemboi\PaymentGateway\Mpesa\StkPush;

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

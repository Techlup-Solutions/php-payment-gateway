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

```php


// import the dependency
use Denniskemboi\PaymentGateway\Mpesa\StkPush;

$data = new StkPush();
$data->setCallbackUrl("{YOUR_API_CALLBACK}") // required
    ->setAmount("{AMOUNT}") // required
    ->setPhone("{PHONE_NUMBER}") // required 254*********
    ->setReference("{REF}") //reuired * e.g account number, room number, etc
    ->setRemarks("your remarks"); // optional

$ response = $data->tillRequestPush();

```
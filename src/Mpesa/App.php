<?php

namespace Techlup\PaymentGateway\Mpesa;

use Dotenv\Dotenv;
use Exception;
use stdClass;

class App
{
    protected $app_root = __DIR__ . '/../..';
    public function __construct($root = __DIR__ . '/../..')
    {
        $this->app_root = $root;
        // initialize dot env
        Dotenv::createImmutable($this->app_root)->load();
    }

    /**
     * @return bool returns false if MPESA_SANDBOX is set to false in .env
     */
    public function isSandbox(): bool{
        if(isset($_ENV['MPESA_SANDBOX'])) return boolval($_ENV['MPESA_SANDBOX']);
        return false;
    }

    // get live access token
    public function getLiveToken()
    {
        $headers = ['Content-Type:application/json; charset=utf8'];
        $access_token_url = 'https://'.$this->isSandbox()?'sandbox':'api'.'.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

        $curl = curl_init($access_token_url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        curl_setopt($curl, CURLOPT_USERPWD, $_ENV['MPESA_CONSUMER_KEY'] . ':' . $_ENV['MPESA_CONSUMER_SECRET']);
        curl_close($curl);
        try {

            $result = curl_exec($curl);
            $data = null;
            if (json_decode($result) == null) {
                $data = new stdClass();
                $data->access_token = "";
            } else $data = json_decode($result);
            $data->status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            return $data;
        } catch (Exception $e) {
            $data->status = 500;
            $data->error = $e->getMessage();
            return $data;
        }
    }

    public function getPassword($timestamp)
    {
        date_default_timezone_set('Africa/Nairobi');
        return base64_encode(
            $_ENV['MPESA_SHOT_CODE'] .
                $_ENV['MPESA_PASS_KEY'] .
                $timestamp
        );
    }
}

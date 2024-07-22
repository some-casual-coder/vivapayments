<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class VivaPaymentService
{
    protected $clientId;
    protected $clientSecret;
    protected $client;

    public function __construct()
    {
        $this->clientId = env('VIVA_CLIENT_ID');
        $this->clientSecret = env('VIVA_CLIENT_SECRET');
        $this->client = new Client();
    }

    public function getAccessToken()
    {
        Log::info('Requesting access token from Viva Wallet API');

        $response = $this->client->post('https://accounts.vivapayments.com/connect/token', [
            'auth' => [$this->clientId, $this->clientSecret],
            'form_params' => [
                'grant_type' => 'client_credentials'
            ]
        ]);

        $data = json_decode($response->getBody(), true);
        Log::info('Received access token from Viva Wallet API', ['access_token' => $data['access_token']]);

        return $data['access_token'];
    }

    public function createOrder($amount, $customerDetails)
    {
        $accessToken = $this->getAccessToken();

        Log::info('Creating order with Viva Wallet API', ['amount' => $amount, 'customerDetails' => $customerDetails]);

        $response = $this->client->post('https://api.vivapayments.com/checkout/v2/orders', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'amount' => $amount,
                'customerTrns' => $customerDetails['customerTrns'],
                'customer' => [
                    'email' => $customerDetails['email'],
                    'fullName' => $customerDetails['fullName'],
                    'requestLang' => $customerDetails['requestLang']
                ],
                'paymentNotification' => true
            ]
        ]);

        $order = json_decode($response->getBody(), true);
        Log::info('Order created successfully', ['order' => $order]);

        return $order;
    }
}

<?php
declare(strict_types=1);

namespace App\Main\Domain\Service;

use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;

class PayPalService
{
    private Client $client;
    private string $clientId;
    private string $clientSecret;
    private string $baseUrl;
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->client = new Client();
        $this->clientId = $_ENV['PAYPAL_CLIENT_ID'];
        $this->clientSecret = $_ENV['PAYPAL_CLIENT_SECRET'];
        $this->baseUrl = 'https://api-m.sandbox.paypal.com';
        $this->logger = $logger;
    }

    public function createPayment(float $amount, string $currency = 'USD'): array
    {
        try {
            $token = $this->getAccessToken();

            $response = $this->client->post($this->baseUrl . '/v1/payments/payment', [
                'headers' => [
                    'Authorization' => "Bearer $token",
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'intent' => 'sale',
                    'payer' => ['payment_method' => 'paypal'],
                    'transactions' => [
                        [
                            'amount' => ['total' => number_format($amount, 2), 'currency' => $currency]
                        ]
                    ],
                    'redirect_urls' => [
                        'return_url' => $_ENV['PAYPAL_RETURN_URL'],
                        'cancel_url' => $_ENV['PAYPAL_CANCEL_URL']
                    ]
                ]
            ]);

            return json_decode((string) $response->getBody(), true);
        } catch (\Exception $e) {
            $this->logger->error("Error creando pago en PayPal: " . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    private function getAccessToken(): string
    {
        $response = $this->client->post($this->baseUrl . '/v1/oauth2/token', [
            'auth' => [$this->clientId, $this->clientSecret],
            'form_params' => [
                'grant_type' => 'client_credentials'
            ]
        ]);

        $data = json_decode((string) $response->getBody(), true);
        return $data['access_token'] ?? '';
    }
}

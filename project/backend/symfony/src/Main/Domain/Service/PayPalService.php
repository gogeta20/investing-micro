<?php
declare(strict_types=1);

namespace App\Main\Domain\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
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
        $this->clientId = $_ENV['PAYPAL_CLIENT_ID'] ?? '';
        $this->clientSecret = $_ENV['PAYPAL_CLIENT_SECRET'] ?? '';
        $this->baseUrl = 'https://api-m.sandbox.paypal.com';
        $this->logger = $logger;
    }

    private function getAccessToken(): ?string
    {
        try {
            $response = $this->client->post($this->baseUrl . '/v1/oauth2/token', [
                'auth' => [$this->clientId, $this->clientSecret],
                'form_params' => ['grant_type' => 'client_credentials']
            ]);

            $data = json_decode((string) $response->getBody(), true);
            return $data['access_token'] ?? null;
        } catch (RequestException $e) {
            $this->logger->error("Error obteniendo token de PayPal: " . $e->getMessage());
            return null;
        }
    }

    public function createOrder(string $id, float $amount, string $currency = 'USD'): array
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return ['error' => 'No se pudo obtener el token de PayPal'];
        }

        try {
            $response = $this->client->post($this->baseUrl . '/v2/checkout/orders', [
                'headers' => [
                    'Authorization' => "Bearer $token",
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'intent' => 'CAPTURE',
                    'purchase_units' => [
                        [
                            'amount' => [
                                'currency_code' => $currency,
                                'value' => number_format($amount, 2, '.', '')
                            ]
                        ]
                    ],
                    'application_context' => [
                        'return_url' => 'https://d92c-92-172-189-157.ngrok-free.app/payments/paypal/success',
                        // 'return_url' => 'https://9d8a-92-172-189-157.ngrok-free.app/payments/paypal/success',
                        // 'return_url' => $_ENV['PAYPAL_RETURN_URL'] ?? 'https://9d8a-92-172-189-157.ngrok-free.app/payments/paypal/success',
                        'cancel_url' => $_ENV['PAYPAL_CANCEL_URL'] ?? 'https://example.com/cancel'
                    ]
                ]
            ]);

            $data = json_decode((string) $response->getBody(), true);
            $this->logger->info('PayPal Order Response: id:'.$id, ['response' => $data]);

            return $data;
        } catch (RequestException $e) {
            $this->logger->error("Error creando orden en PayPal: " . $e->getMessage());
            return ['error' => 'Error al crear la orden de PayPal'];
        }
    }

    public function captureOrder(string $orderId): array
    {
        // $orderId = "5AL84419GP7713631";
        $token = $this->getAccessToken();
        if (!$token) {
            return ['error' => 'No se pudo obtener el token de PayPal'];
        }

        try {
            $response = $this->client->post($this->baseUrl . "/v2/checkout/orders/{$orderId}/capture", [
                'headers' => [
                    'Authorization' => "Bearer $token",
                    'Content-Type' => 'application/json',
                ]
            ]);

            $data = json_decode((string) $response->getBody(), true);
            $this->logger->info('PayPal Capture Response:', ['response' => $data]);

            return $data;
        } catch (RequestException $e) {
            $this->logger->error("Error capturando pago en PayPal: " . $e->getMessage());
            return ['error' => 'Error al capturar el pago de PayPal'];
        }
    }
}

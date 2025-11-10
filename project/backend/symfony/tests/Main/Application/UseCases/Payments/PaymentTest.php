<?php
declare(strict_types=1);
namespace App\Tests\Main\Application\UseCases\Payments;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;

class PaymentTest extends TestCase
{
    public function testPayPalPaymentRequest()
    {
        // Mockeamos la respuesta de PayPal (suponiendo una respuesta exitosa de la API)
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'id' => 'PAY-123456789',
                'status' => 'created',
                'links' => [
                    ['rel' => 'approve', 'href' => 'https://sandbox.paypal.com/checkoutnow?token=PAY-123456789']
                ]
            ]))
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        // Simulamos la solicitud al backend para procesar un pago con PayPal
        $response = $client->post('https://api.sandbox.paypal.com/v1/payments/payment', [
            'json' => [
                'intent' => 'sale',
                'payer' => ['payment_method' => 'paypal'],
                'transactions' => [
                    [
                        'amount' => ['total' => '10.00', 'currency' => 'USD']
                    ]
                ],
                'redirect_urls' => [
                    'return_url' => 'https://miapp.com/return',
                    'cancel_url' => 'https://miapp.com/cancel'
                ]
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $body = json_decode((string) $response->getBody(), true);

        $this->assertArrayHasKey('id', $body);
        $this->assertEquals('PAY-123456789', $body['id']);
        $this->assertEquals('created', $body['status']);
        $this->assertNotEmpty($body['links']);
    }
}

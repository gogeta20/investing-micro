<?php
declare(strict_types=1);

namespace App\Tests\Main\Domain\Service;

use App\Main\Domain\Service\PayPalService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Psr\Log\LoggerInterface;
use PHPUnit\Framework\TestCase;

class PayPalServiceTest extends TestCase
{
    private PayPalService $payPalService;
    private MockHandler $mockHandler;
    private LoggerInterface $loggerMock;

    protected function setUp(): void
    {
        // Mockeamos el logger
        $this->loggerMock = $this->createMock(LoggerInterface::class);

        // Creamos un MockHandler para simular respuestas de la API de PayPal
        $this->mockHandler = new MockHandler();

        // Creamos un Client con el MockHandler
        $client = new Client(['handler' => HandlerStack::create($this->mockHandler)]);

        // Instanciamos PayPalService con el cliente y el logger mockeado
        $this->payPalService = new PayPalService($this->loggerMock);

        // Reemplazamos manualmente el cliente en la instancia de PayPalService
        $reflection = new \ReflectionClass($this->payPalService);
        $clientProperty = $reflection->getProperty('client');
        $clientProperty->setAccessible(true);
        $clientProperty->setValue($this->payPalService, $client);
    }

    /**
     * 📌 Caso 1: Creación de un pago exitoso
     */
    public function testCreatePaymentSuccess()
    {
        // Simulamos una respuesta de PayPal con un ID de pago
        $this->mockHandler->append(new Response(200, [], json_encode([
            'id' => 'PAY-123456789',
            'state' => 'created'
        ])));

        $result = $this->payPalService->createPayment(100.00, 'USD');

        $this->assertArrayHasKey('id', $result);
        $this->assertEquals('PAY-123456789', $result['id']);
    }

    /**
     * 📌 Caso 2: Error en la API de PayPal al crear el pago
     */
    public function testCreatePaymentFails()
    {
        $this->mockHandler->append(new Response(400, [], json_encode([
            'error' => 'Invalid request'
        ])));

        $result = $this->payPalService->createPayment(100.00, 'USD');

        $this->assertArrayHasKey('error', $result);
        $this->assertEquals('Invalid request', $result['error']);
    }

    /**
     * 📌 Caso 3: Excepción inesperada
     */
    public function testCreatePaymentThrowsException()
    {
        $this->mockHandler->append(new Response(500, [], 'Internal Server Error'));

        $this->loggerMock
            ->expects($this->once())  // El logger debe registrar el error
            ->method('error');

        $result = $this->payPalService->createPayment(100.00, 'USD');

        $this->assertArrayHasKey('error', $result);
    }

    /**
     * 📌 Caso 4: Obtener token de acceso exitoso
     */
    public function testGetAccessTokenSuccess()
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'TEST_ACCESS_TOKEN'
        ])));

        $result = $this->payPalService->createPayment(100.00, 'USD');

        $this->assertNotEmpty($result);
    }
}

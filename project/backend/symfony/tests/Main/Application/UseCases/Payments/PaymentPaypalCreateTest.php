<?php

declare(strict_types=1);

namespace App\Tests\Main\Application\UseCases\Payments\Paypal;

use App\Main\Application\UseCases\Payments\Paypal\PaymentPaypalCreate;
use App\Main\Application\UseCases\Payments\Paypal\PaymentPaypalCommand;
use App\Main\Domain\Exception\StoreException;
use App\Main\Domain\Service\PayPalService;
use App\Shared\Domain\Bus\Event\EventBus;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\CoversClass;


#[CoversClass(PaymentPaypalCreate::class)]
class PaymentPaypalCreateTest extends TestCase
{
    private PaymentPaypalCreate $paymentPaypal;
    private $paypalServiceMock;
    private $eventBusMock;

    protected function setUp(): void
    {
        $this->paypalServiceMock = $this->createMock(PayPalService::class);
        $this->eventBusMock = $this->createMock(EventBus::class);

        $this->paymentPaypal = new PaymentPaypalCreate(
            bus: $this->eventBusMock,
            paypalService: $this->paypalServiceMock
        );
    }

    /**
     * 📌 Data Provider: Devuelve diferentes combinaciones de valores
     */
    public static function paymentDataProvider(): array
    {
        return [
            'Caso 1: Pago en USD' => [100.00, 'USD'],
            'Caso 2: Pago en EUR' => [50.00, 'EUR'],
            'Caso 3: Pago en GBP' => [75.50, 'GBP'],
            'Caso 4: Pago con decimal' => [99.99, 'USD'],
            'Caso 5: Pago con número grande' => [1000.00, 'EUR'],
        ];
    }

    #[DataProvider('paymentDataProvider')]
    public function testSuccessfulPaymentWithDifferentAmounts(float $amount, string $currency)
    {
        $paypalResponse = [
            'id' => 'PAY-123456789',
            'state' => 'created'
        ];

        $this->paypalServiceMock
            ->expects($this->once())
            ->method('createPayment')
            ->with($amount, $currency)
            ->willReturn($paypalResponse);

        $command = new PaymentPaypalCommand($amount, $currency);
        $this->paymentPaypal->__invoke($command);

        $this->assertArrayHasKey('id', $paypalResponse);
    }

    public function testPaymentFailsAndThrowsException()
    {
        // Simulamos una respuesta con error
        $paypalErrorResponse = ['error' => 'Invalid credentials'];

        // Configuramos el mock para devolver una respuesta con error
        $this->paypalServiceMock
            ->expects($this->once())
            ->method('createPayment')
            ->with(50.00, 'EUR')
            ->willReturn($paypalErrorResponse);

        // Esperamos que lance StoreException
        $this->expectException(StoreException::class);
        $this->expectExceptionMessage('PayPal error: Invalid credentials');

        $command = new PaymentPaypalCommand(50.00, 'EUR');
        $this->paymentPaypal->__invoke($command);
    }
    // public function testSuccessfulPayment()
    // {
    //     // Simulamos una respuesta exitosa de PayPal
    //     $paypalResponse = [
    //         'id' => 'PAY-123456789',
    //         'state' => 'created'
    //     ];

    //     // Configuramos el mock para que devuelva una respuesta exitosa
    //     $this->paypalServiceMock
    //         ->expects($this->once())
    //         ->method('createPayment')
    //         ->with(100.00, 'USD')
    //         ->willReturn($paypalResponse);

    //     // No esperamos que se lance una excepción
    //     $command = new PaymentPaypalCommand(100.00, 'USD');
    //     $this->paymentPaypal->__invoke($command);

    //     $this->assertArrayHasKey('id', $paypalResponse, 'El ID del pago no está presente en la respuesta.');
    //     $this->assertArrayHasKey('state', $paypalResponse, 'El estado del pago no está presente en la respuesta.');
        // $this->assertEquals('created', $paypalResponse['state'], 'El estado del pago no es el esperado.');
        // Opcional: Verificar que se dispara un evento en EventBus
        // $this->eventBusMock
        //     ->expects($this->once())
        //     ->method('publish');
    // }
}

<?php

declare(strict_types=1);

namespace App\Tests\Main\Application\UseCases\Payments\Paypal\Confirm;

use App\Main\Application\UseCases\Payments\Paypal\Confirm\PaymentPaypalConfirm;
use App\Main\Application\UseCases\Payments\Paypal\Confirm\PaymentPaypalConfirmCommand;
use App\Main\Domain\Exception\StoreException;
use App\Main\Domain\Service\PayPalService;
use App\Shared\Domain\Bus\Event\EventBus;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(PaymentPaypalConfirm::class)]
class PaymentPaypalConfirmTest extends TestCase
{
    private PaymentPaypalConfirm $paymentPaypalConfirm;
    private $paypalServiceMock;
    private $eventBusMock;

    protected function setUp(): void
    {
        $this->paypalServiceMock = $this->createMock(PayPalService::class);
        $this->eventBusMock = $this->createMock(EventBus::class);

        $this->paymentPaypalConfirm = new PaymentPaypalConfirm(
            bus: $this->eventBusMock,
            paypalService: $this->paypalServiceMock
        );
    }

    public static function paymentDataProvider(): array
    {
        return [
            'Caso 1: Confirmación Exitosa' => ['PAY-123456789', 'PAYER-987654321', true],
            'Caso 2: Error de PayPal' => ['PAY-000000000', 'PAYER-000000000', false],
        ];
    }

    #[DataProvider('paymentDataProvider')]
    public function testSuccessfulPaymentConfirmation(string $paymentId, string $payerId, bool $isSuccessful)
    {
        $paypalResponse = $isSuccessful
            ? ['id' => $paymentId, 'state' => 'approved']
            : ['error' => 'Payment failed'];

        $this->paypalServiceMock
            ->expects($this->once())
            ->method('executePayment')
            ->with($paymentId, $payerId)
            ->willReturn($paypalResponse);

        if (!$isSuccessful) {
            $this->expectException(StoreException::class);
        }

        $command = new PaymentPaypalConfirmCommand($paymentId, $payerId);
        $result = $this->paymentPaypalConfirm->__invoke($command);

        if ($isSuccessful) {
            $this->assertArrayHasKey('id', $result);
            $this->assertEquals('approved', $result['state']);
        }
    }
}

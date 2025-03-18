<?php
declare(strict_types=1);

namespace App\Tests\Integration\Domain\Service\Payments;

use App\Main\Domain\Service\PayPalService;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Psr\Log\NullLogger;

#[CoversClass(PayPalService::class)]
class PayPalServiceIntegrationTest extends TestCase
{
    private PayPalService $payPalService;

    protected function setUp(): void
    {
        // Verificamos si las credenciales de PayPal están configuradas
        if (!isset($_ENV['PAYPAL_CLIENT_ID']) || !isset($_ENV['PAYPAL_CLIENT_SECRET'])) {
            $this->markTestSkipped('No PayPal credentials found.');
        }

        $this->payPalService = new PayPalService(new NullLogger());
    }

    /**
     * 📌 Test de integración real con la API de PayPal
     */
    public function testCreatePaymentWithRealApi()
    {
        $result = $this->payPalService->createPayment(10.00, 'USD');

        // print_r($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertEquals('created', $result['state']);
    }
}

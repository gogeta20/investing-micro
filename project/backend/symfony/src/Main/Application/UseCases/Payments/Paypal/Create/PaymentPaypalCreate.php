<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Payments\Paypal;

use App\Main\Domain\Exception\StoreException;
use App\Shared\Domain\Bus\Event\EventBus;
use App\Main\Domain\Service\PayPalService;

readonly class PaymentPaypalCreate
{
    public function __construct(
        private EventBus $bus,
        private PayPalService $paypalService,
    ) {}

    /**
     * @throws StoreException
     */
    public function __invoke(PaymentPaypalCommand $command): void
    {

      try {
            $response = $this->paypalService->createPayment(
                $command->amount(),
                $command->currency()
            );

            if (isset($response['error'])) {
                throw new StoreException('PayPal error: ' . $response['error']);
            }

            // Opcional: Disparar evento si necesitas registrar pagos exitosos
            // $this->bus->publish(new PaymentCreatedEvent($response['id'], $command->amount(), $command->currency()));

        } catch (\Exception $e) {
            throw new StoreException('Error al procesar el pago: ' . $e->getMessage());
        }
    }
}

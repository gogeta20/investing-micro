<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Payments\Paypal\Confirm;

use App\Main\Domain\Exception\StoreException;
use App\Shared\Domain\Bus\Event\EventBus;
use App\Main\Domain\Service\PayPalService;

readonly class PaymentPaypalConfirm
{
     public function __construct(
        private EventBus $bus,
        private PayPalService $paypalService,
    ) {}

    /**
     * 📌 Ejecuta la confirmación del pago en PayPal
     * @throws StoreException
     */
    public function __invoke(PaymentPaypalConfirmCommand $command): array
    {
        try {
            $response = $this->paypalService->captureOrder(
                $command->paymentId()
            );

            if (isset($response['error'])) {
                throw new StoreException('Error en la confirmación de pago: ' . $response['error']);
            }

            // Opcional: Disparar un evento si queremos registrar pagos exitosos
            // $this->bus->publish(new PaymentConfirmedEvent($response['id'], $command->paymentId()));

            return $response;
        } catch (\Exception $e) {
            throw new StoreException('Error al confirmar el pago: ' . $e->getMessage());
        }
    }
}

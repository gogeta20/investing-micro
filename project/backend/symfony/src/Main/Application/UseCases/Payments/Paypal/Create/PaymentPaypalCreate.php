<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Payments\Paypal\Create;

use App\Main\Domain\Exception\StoreException;
use App\Main\Domain\Model\Interfaces\IBaseRepository;
use App\Shared\Domain\Bus\Event\EventBus;
use App\Main\Domain\Service\PayPalService;
use App\Main\Domain\Model\Payment;

readonly class PaymentPaypalCreate
{
    public function __construct(
        private EventBus $bus,
        private PayPalService $paypalService,
         private IBaseRepository $IBaseRepository,
    ) {}

    /**
     * @throws StoreException
     */
    public function __invoke(PaymentPaypalCommand $command): void
    {

      try {
            $response = $this->paypalService->createOrder(
                $command->id(),
                $command->amount(),
                $command->currency()
            );

            if (isset($response['error'])) {
                throw new StoreException('PayPal error: ' . $response['error']);
            }

            // Opcional: Disparar evento si necesitas registrar pagos exitosos
            // $this->bus->publish(new PaymentCreatedEvent($response['id'], $command->amount(), $command->currency()));

            $payment = Payment::create(
                $command->id(),
                $response['id'] ?? '',
                $response['links'][1]['href'] ?? '',
                $response['type'] ?? 'one-time',
                $response['platform'] ?? 'paypal',
                $command->amount(),
                $command->currency(),
                $response['customer_email'] ?? '',
                $response['status'] ?? 'CREATED',
            );
            $this->IBaseRepository->save($payment);

        } catch (\Exception $e) {
            throw new StoreException('Error al procesar el pago: ' . $e->getMessage());
        }
    }
}

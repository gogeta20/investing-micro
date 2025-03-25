<?php
declare(strict_types=1);

namespace App\Main\Infrastructure\Controller\Payments\Confirm;

use App\Main\Application\UseCases\Payments\Paypal\Confirm\PaymentPaypalConfirmCommand;
use App\Shared\Infrastructure\Symfony\ApiController;
use App\Main\Infrastructure\Response\JsonApiResponse;
use App\Main\Domain\Exception\StoreException;
use App\Shared\Domain\StandardApiResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Exception;

class PaymentPaypalConfirmController extends ApiController
{
    public function __invoke(PaymentPaypalConfirmRequest $request): JsonResponse
    {
        $errors = $request->validate();

        if (null !== $errors) {
          return JsonApiResponse::error(errors: $errors);
        }
        // $paymentId = $request->query->get('paymentId');
        // $payerId = $request->query->get('PayerID');

        // if (!$paymentId || !$payerId) {
        //     return JsonApiResponse::error(errors: ['Faltan parámetros en la URL']);
        // }

        try {
            $this->dispatch(new PaymentPaypalConfirmCommand(
              $request->data()['paymentId']
              // $request->data()['PayerID']
            ));

            return (new StandardApiResponse(
                data: [],
                message: 'Pago confirmado correctamente',
                code: 200
            ))->__invoke();
        } catch (\Exception $e) {
            throw new StoreException('Error al confirmar el pago: ' . $e->getMessage());
        }
    }

    protected function exceptions(): array
    {
        return [
            Exception::class => 500,
        ];
    }
}

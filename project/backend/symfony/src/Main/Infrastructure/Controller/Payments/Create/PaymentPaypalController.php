<?php
declare(strict_types=1);

namespace App\Main\Infrastructure\Controller\Payments\Create;

use App\Main\Infrastructure\Controller\Payments\Create\PaymentPaypalRequest;
use App\Main\Application\UseCases\Payments\Paypal\Create\PaymentPaypalCommand;
use App\Shared\Infrastructure\Symfony\ApiController;
use App\Main\Infrastructure\Response\JsonApiResponse;
use App\Main\Domain\Exception\StoreException;
use App\Shared\Domain\StandardApiResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Exception;

class PaymentPaypalController extends ApiController
{
    public function __invoke(PaymentPaypalRequest $request): JsonResponse
    {
      $errors = $request->validate();

      if (null !== $errors) {
        return JsonApiResponse::error(errors: $errors);
      }

      try {
        $this->dispatch(new PaymentPaypalCommand(
          $request->data()['id'],
          $request->data()['amount'],
          $request->data()['currency']
        ));

        return (new StandardApiResponse(
            data: [],
            message: 'Payment successfully',
            code: 200
        ))->__invoke();

      }catch(\Exception $e){
        throw new StoreException('Error al crear el pago: ' . $e->getMessage() );
      }
    }

    protected function exceptions(): array
    {
        return [
            Exception::class => 500,
        ];
    }
}

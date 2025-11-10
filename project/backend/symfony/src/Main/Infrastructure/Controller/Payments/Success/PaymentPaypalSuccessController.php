<?php

declare(strict_types=1);

namespace App\Main\Infrastructure\Controller\Payments\Success;

use App\Main\Domain\Exception\StoreException;
use App\Shared\Domain\BaseResponse;
use App\Shared\Domain\StandardApiResponse;
use App\Main\Infrastructure\Response\JsonApiResponse;
use App\Shared\Infrastructure\Symfony\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Main\Application\UseCases\Payments\Paypal\Approve\PaymentApprovePaypalQuery;
use Symfony\Component\BrowserKit\Response;

class PaymentPaypalSuccessController extends ApiController
{
    /**
     * @throws StoreException
     */
    public function __invoke()
    // : JsonResponse
    {
          return new Response('✅ Pago aprobado. ¡Hola desde Ngrok y Symfony en Docker!');

      //  $errors = $request->validate();

      //   if (null !== $errors) {
      //     return JsonApiResponse::error(errors: $errors);
      //   }

      //   try {
      //       /** @var BaseResponse $response */
      //       $response = $this->ask(
      //           new PaymentApprovePaypalQuery($id)
      //       );

      //       return (new StandardApiResponse(
      //           data: $response->data(),
      //           message: $response->message(),
      //           code: $response->status()
      //       ))->__invoke();
      //   } catch (\Exception $exception) {
      //       throw new StoreException('Error al obtener el usuario : ' . $exception->getMessage());
      //   }
    }

    protected function exceptions(): array
    {
        return [
            StoreException::class => 500,
            \Exception::class => 503,
        ];
    }
}

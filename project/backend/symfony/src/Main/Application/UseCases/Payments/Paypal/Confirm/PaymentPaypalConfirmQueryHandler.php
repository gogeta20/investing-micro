<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Payments\Paypal\Confirm;

use App\Main\Domain\Exception\StoreException;
use App\Shared\Domain\BaseResponse;
use App\Shared\Domain\Bus\Command\CommandHandler;
use App\Shared\Domain\Interfaces\TranslateInterfaceCustom;
use Symfony\Component\HttpFoundation\Response;

final class PaymentPaypalConfirmQueryHandler implements CommandHandler
{
    public function __construct(
        private readonly PaymentPaypalConfirm $paypal,
        protected TranslateInterfaceCustom $translatorCustom
    ) {}

    /**
     * @throws StoreException
     */
    public function __invoke(PaymentPaypalConfirmQuery $query): BaseResponse
    {
        try {
            $response = $this->paypal->__invoke($query);
            // Aquí puedes manejar la respuesta de PayPal y crear un objeto de respuesta
            list($status, $message, $data) = $this->resolveResponseParams($response);
            $response = new BaseResponse($data);
            $response->setStatus($status);
            $response->setMessage($message);
            return $response;
        } catch (\Exception $e) {
            throw new StoreException('Error al crear el artículo: ' . $e->getMessage());
        }
    }

    private function resolveResponseParams(array $result): array
    {
        if (empty($result)) {
            $message = $this->translatorCustom->translate('empty', [], 'basic');
        } else {
            $message = $this->translatorCustom->translate('success', [], 'basic');
        }
        $status = Response::HTTP_CREATED;
        $data = $result;
        return array($status, $message, $data);
    }
}

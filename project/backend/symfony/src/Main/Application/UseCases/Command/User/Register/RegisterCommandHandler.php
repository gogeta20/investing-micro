<?php
declare(strict_types=1);

namespace App\Main\Application\UseCases\Command\User\Register;

use App\Main\Domain\Exception\StoreException;
use App\Shared\Domain\BaseResponse;
use App\Shared\Domain\Bus\Command\CommandHandler;
use App\Shared\Domain\Interfaces\TranslateInterfaceCustom;
use Symfony\Component\HttpFoundation\Response;

final class RegisterCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly Register $register,
        protected TranslateInterfaceCustom $translatorCustom
    ) {}

    /**
     * @throws StoreException
     */
    public function __invoke(RegisterCommand $command): BaseResponse
    {
        try {
            $result = $this->register->__invoke($command);
            list($status, $message, $data) = $this->resolveResponseParams($result);
            $response = new BaseResponse($data);
            $response->setStatus($status);
            $response->setMessage($message);
            return $response;
        } catch (\Exception $e) {
            throw new StoreException('Registro : ' . $e->getMessage());
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

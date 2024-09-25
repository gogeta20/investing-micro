<?php
declare(strict_types=1);

namespace App\Main\Infrastructure\Controller\User\Register;

use App\Main\Application\UseCases\Command\User\Register\RegisterCommand;
use App\Main\Domain\Exception\StoreException;
use App\Main\Infrastructure\Response\JsonApiResponse;
use App\Shared\Application\AppConstants;
use App\Shared\Infrastructure\Symfony\ApiController;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

class RegisterController extends ApiController
{
    /**
     * @throws StoreException
     */
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $errors = $request->validate();

        if (null !== $errors) {
            return JsonApiResponse::error(errors: $errors);
        }

        try {
            $this->dispatch(RegisterCommand::create($request->data()));
        } catch (Exception $exception) {
            throw new StoreException("Error " .$exception->getMessage());
        }

        return JsonApiResponse::created($this->translator->translate(AppConstants::SUCCESS, [], 'basic'));
    }

    protected function exceptions(): array
    {
        return [
            StoreException::class => 500,
            Exception::class => 503,
        ];
    }
}

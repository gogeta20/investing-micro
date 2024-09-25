<?php
declare(strict_types=1);

namespace App\Main\Infrastructure\Controller\User\Edit;

use App\Main\Application\UseCases\Command\User\Edit\EditUserCommand;
use App\Main\Domain\Exception\StoreException;
use App\Main\Infrastructure\Response\JsonApiResponse;
use App\Shared\Application\AppConstants;
use App\Shared\Infrastructure\Symfony\ApiController;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class EditUserController extends ApiController
{
    /**
     * @throws StoreException
     */
    public function __invoke(EditUserRequest $request, Request $requestHttp): JsonResponse
    {
        $errors = $request->validate();

        if (null !== $errors) {
            return JsonApiResponse::error(errors: $errors);
        }

        $user = $this->decoderService->getUserFromToken($requestHttp);
        try {
            $this->dispatch(EditUserCommand::create([
                    'email' => $user,
                    'new_email' => $request->data()['new_email'] ?? null,
                    'username' => $request->data()['username'] ?? null,
                    'password' => $request->data()['password'] ?? null
                ])
            );
        } catch (Exception $exception) {
            throw new StoreException('Error updating profile: ' . $exception->getMessage());
        }

        return JsonApiResponse::updated($this->translator->translate(AppConstants::SUCCESS, [], 'basic'));
    }

    protected function exceptions(): array
    {
        return [
            StoreException::class => 500,
            Exception::class => 503,
        ];
    }
}

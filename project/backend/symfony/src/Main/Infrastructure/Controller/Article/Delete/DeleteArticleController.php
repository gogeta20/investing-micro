<?php

declare(strict_types=1);

namespace App\Main\Infrastructure\Controller\Article\Delete;

use App\Main\Application\UseCases\Command\Article\DeleteArticle\DeleteArticleCommand;
use App\Main\Domain\Exception\StoreException;
use App\Main\Infrastructure\Response\JsonApiResponse;
use App\Shared\Application\AppConstants;
use App\Shared\Infrastructure\Symfony\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Exception;

class DeleteArticleController extends ApiController
{
    /**
     * @throws StoreException
     */
    public function __invoke(DeleteArticleRequest $request, string $id): JsonResponse
    {
        $errors = $request->validate();

        if (null !== $errors) {
            return JsonApiResponse::error(errors: $errors);
        }

        try {
            $this->dispatch(DeleteArticleCommand::create($id));
        } catch (Exception $exception) {
            throw new StoreException('Error al eliminar el artículo: ' . $exception->getMessage());
        }

        return JsonApiResponse::deleted($this->translator->translate(AppConstants::SUCCESS, [], 'basic'));
    }

    protected function exceptions(): array
    {
        return [
            StoreException::class => 500,
            Exception::class => 503,
        ];
    }
}

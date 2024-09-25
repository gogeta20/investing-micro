<?php

declare(strict_types=1);

namespace App\Main\Infrastructure\Controller\Article\Post\Favorite;

use App\Main\Application\UseCases\Command\Article\Favorite\Create\FavoriteCommand;
use App\Main\Infrastructure\Response\JsonApiResponse;
use App\Shared\Application\AppConstants;
use App\Shared\Infrastructure\Symfony\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Main\Domain\Exception\StoreException;
use Symfony\Component\HttpFoundation\Request;

class PostFavoriteController extends ApiController
{
    /**
     * @throws StoreException
     */
    public function __invoke(FavoriteRequest $request, Request $httpRequest, string $id): JsonResponse
    {
        $errors = $request->validate();

        if (null !== $errors) {
            return JsonApiResponse::error(errors: $errors);
        }

        $email = $this->decoderService->getUserFromToken($httpRequest);

        try {
            $this->dispatch(FavoriteCommand::create([
                'email' => $email,
                'articleId' => $id
            ]));

        } catch (\Exception $exception) {
            throw new StoreException('Error al marcar el artículo como favorito: ' . $exception->getMessage());
        }

        return JsonApiResponse::created($this->translator->translate(AppConstants::SUCCESS, [], 'basic'));
    }

    protected function exceptions(): array
    {
        return [
            StoreException::class => 500,
            \Exception::class => 503,
        ];
    }
}

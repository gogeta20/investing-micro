<?php

declare(strict_types=1);

namespace App\Main\Infrastructure\Controller\Article\Post\Rating;

use App\Main\Application\UseCases\Command\Article\Rating\Create\CreateRatingCommand;
use App\Main\Domain\Exception\StoreException;
use App\Main\Infrastructure\Response\JsonApiResponse;
use App\Shared\Application\AppConstants;
use App\Shared\Infrastructure\Symfony\ApiController;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PostRatingController extends ApiController
{
    /**
     * @throws StoreException
     */
    public function __invoke(RatingRequest $request, Request $httpRequest, string $id): JsonResponse
    {
        $errors = $request->validate();

        if (null !== $errors) {
            return JsonApiResponse::error(errors: $errors);
        }

        $email = $this->decoderService->getUserFromToken($httpRequest);
        try {
            $this->dispatch(CreateRatingCommand::create([
                'email' => $email,
                'articleId' => $id,  // id del artículo
                'rating' => $request->data()['rating'],        // calificación de 1 a 5
                'comment' => $request->data()['comment'] ?? null, // comentario opcional
            ]));

        } catch (Exception $exception) {
            throw new StoreException('Error al calificar el artículo: ' . $exception->getMessage());
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

<?php

declare(strict_types=1);

namespace App\Main\Infrastructure\Controller\Article\Get;

use App\Main\Application\UseCases\Querys\Article\Favorite\All\GetAllFavoritesQuery;
use App\Main\Domain\Exception\StoreException;
use App\Main\Infrastructure\Response\JsonApiResponse;
use App\Shared\Domain\BaseResponse;
use App\Shared\Infrastructure\Symfony\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetAllFavoritesController extends ApiController
{
    /**
     * @throws StoreException
     */
    public function __invoke(Request $httpRequest): JsonResponse
    {
        $email = $this->decoderService->getUserFromToken($httpRequest);

        try {
            /** @var BaseResponse $response */
            $response = $this->ask(new GetAllFavoritesQuery($email));
            return JsonApiResponse::success(
                $response->data(),
                $response->message(),
                $response->status()
            );
        } catch (\Exception $exception) {
            throw new StoreException('Error al obtener los favoritos: ' . $exception->getMessage());
        }
    }

    protected function exceptions(): array
    {
        return [
            StoreException::class => 500,
            \Exception::class => 503,
        ];
    }
}

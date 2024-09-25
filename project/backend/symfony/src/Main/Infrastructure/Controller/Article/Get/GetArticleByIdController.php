<?php

declare(strict_types=1);

namespace App\Main\Infrastructure\Controller\Article\Get;

use App\Main\Application\UseCases\Querys\Article\ById\GetArticleByIdQuery;
use App\Main\Domain\Exception\StoreException;
use App\Main\Infrastructure\Response\JsonApiResponse;
use App\Shared\Domain\BaseResponse;
use App\Shared\Infrastructure\Symfony\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetArticleByIdController extends ApiController
{
    /**
     * @throws StoreException
     */
    public function __invoke(string $id): JsonResponse
    {
        try {
            /** @var BaseResponse $response */
            $response = $this->ask(new GetArticleByIdQuery($id));
            return JsonApiResponse::success(
                $response->data(),
                $response->message(),
                $response->status()
            );
        } catch (\Exception $exception) {
            throw new StoreException('Error al obtener el artículo con ID: ' . $exception->getMessage());
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

<?php
declare(strict_types=1);
namespace App\Main\Infrastructure\Controller\Article\Get;

use App\Main\Application\UseCases\Querys\Article\ArticleQuery;
use App\Main\Domain\Exception\StoreException;
use App\Main\Infrastructure\Response\JsonApiResponse;
use App\Shared\Domain\StandardApiResponse;
use App\Shared\Infrastructure\Symfony\ApiController;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetArticlesController extends ApiController
{
    public function __invoke(): JsonResponse
    {

        try {
            $response = $this->ask(
                new ArticleQuery()
            );
            return (new StandardApiResponse(
                data: $response->data(),
                message: $response->message(),
                code: $response->status()
            ))->__invoke();

        } catch (StoreException|Exception $exception) {
            return JsonApiResponse::error($exception->getTrace(),$exception->getMessage(), $exception->getCode());
        }

    }

    protected function exceptions(): array
    {
        return [
            StoreException::class => 500,
            Exception::class => 503,
        ];
    }
}

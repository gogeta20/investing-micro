<?php

declare(strict_types=1);

namespace App\Main\Infrastructure\Controller\User\Get;

use App\Main\Application\UseCases\Querys\Article\ById\GetArticleByIdQuery;
use App\Main\Application\UseCases\Querys\User\ById\GetUserByIdQuery;
use App\Main\Domain\Exception\StoreException;
use App\Main\Infrastructure\Response\JsonApiResponse;
use App\Shared\Domain\BaseResponse;
use App\Shared\Infrastructure\Symfony\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetUserByIdController extends ApiController
{
    /**
     * @throws StoreException
     */
    public function __invoke(Request $request): JsonResponse
    {
        $user = $this->decoderService->getUserFromToken($request);
        try {
            /** @var BaseResponse $response */
            $response = $this->ask(new GetUserByIdQuery($user));
            return JsonApiResponse::success(
                $response->data(),
                $response->message(),
                $response->status()
            );
        } catch (\Exception $exception) {
            throw new StoreException('Error al obtener el usuario : ' . $exception->getMessage());
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

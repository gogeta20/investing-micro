<?php

declare(strict_types=1);

namespace App\Main\Infrastructure\Controller\Pokemon\Get;

use App\Main\Application\UseCases\Pokemon\Get\PokemonGetQuery;
use App\Main\Domain\Exception\StoreException;
use App\Shared\Domain\BaseResponse;
use App\Shared\Domain\StandardApiResponse;
use App\Shared\Infrastructure\Symfony\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetPokemonByIdController extends ApiController
{
    /**
     * @throws StoreException
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            /** @var BaseResponse $response */
            $response = $this->ask(
                new PokemonGetQuery()
            );

            return (new StandardApiResponse(
                data: $response->data(),
                message: $response->message(),
                code: $response->status()
            ))->__invoke();
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

<?php

declare(strict_types=1);

namespace App\Main\Infrastructure\Controller\Pokemon\Post;

use App\Main\Application\UseCases\Pokemon\Create\PokemonCreateCommand;
use App\Main\Application\UseCases\Pokemon\Get\PokemonGetQuery;
use App\Main\Domain\Exception\StoreException;
use App\Shared\Domain\BaseResponse;
use App\Shared\Domain\StandardApiResponse;
use App\Shared\Infrastructure\Symfony\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CreatePokemonController extends ApiController
{
    /**
     * @throws StoreException
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
             $this->dispatch(new PokemonCreateCommand());

            return (new StandardApiResponse(
                data: [],
                message: 'Pokemon created successfully',
                code: 200
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

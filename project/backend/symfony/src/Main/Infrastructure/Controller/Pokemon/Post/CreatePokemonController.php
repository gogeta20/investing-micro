<?php

declare(strict_types=1);

namespace App\Main\Infrastructure\Controller\Pokemon\Post;

use App\Main\Application\UseCases\Pokemon\Create\PokemonCreateCommand;
use App\Main\Domain\Exception\StoreException;
use App\Main\Infrastructure\Response\JsonApiResponse;
use App\Shared\Domain\StandardApiResponse;
use App\Shared\Infrastructure\Symfony\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CreatePokemonController extends ApiController
{
    /**
     * @throws StoreException
     */
    public function __invoke(Request $requestHttp, CreatePokemonRequest $request): JsonResponse
    {
        $errors = $request->validate();

        if (null !== $errors) {
            return JsonApiResponse::error(errors: $errors);
        }

        try {
             $this->dispatch(new PokemonCreateCommand(
                 $request->data()['nombre'],
                 $request->data()['ataque'],
                 $request->data()['defensa'],
                 $request->data()['velocidad'],
                 $request->data()['especial'],
                 $request->data()['peso'],
                 $request->data()['altura'],
                 $request->data()['ps'],
             ));

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

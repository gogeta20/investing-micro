<?php

declare(strict_types=1);

namespace App\Main\Infrastructure\Controller\Health;

use App\Main\Application\UseCases\Querys\Test\Check\CheckQuery;
use App\Shared\Domain\BaseResponse;
use App\Shared\Domain\StandardApiResponse;
use App\Shared\Infrastructure\Symfony\ApiController;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class CheckController extends ApiController
{
    public function __invoke(Request $request, string $email): JsonResponse
    {
//        $data = json_decode($request->getContent(), true);
//        $email = $data['email'] ?? null;
        /** @var BaseResponse $response */
        $response = $this->ask(
            new CheckQuery($email)
        );

        return (new StandardApiResponse(
            data: $response->data(),
            message: $response->message(),
            code: $response->status()
        ))->__invoke();
    }

    protected function exceptions(): array
    {
        return [
            Exception::class => 500,
        ];
    }
}

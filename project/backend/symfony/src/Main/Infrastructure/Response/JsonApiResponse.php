<?php

declare(strict_types=1);

namespace App\Main\Infrastructure\Response;

use Symfony\Component\HttpFoundation\JsonResponse as SymfonyJsonResponse;

class JsonApiResponse extends SymfonyJsonResponse implements JsonApiResponseInterface
{
    protected function __construct(
        protected int $code,
        protected array $body
    ) {
        parent::__construct(data: $body, status: $code, headers: []);
    }

    public static function created(string $message): JsonApiResponse
    {
        return new self(
            code: 201,
            body: [
                'status'  => 'success',
                'data'    => null,
                'errors'  => null,
                'message' => $message,
            ],
        );
    }

    public static function deleted(string $message): JsonApiResponse
    {
        return new self(
            code: 200,
            body: [
                'status'  => 'success',
                'data'    => null,
                'errors'  => null,
                'message' => $message,
            ],
        );
    }

    public static function error(array|null $errors = null, string|null $message = null, int $code = 400): JsonApiResponse
    {
        assert($code >= 400 && $code < 500);

        return new self(
            code: $code,
            body: [
                'status'  => 'error',
                'data'    => null,
                'errors'  => $errors,
                'message' => $message,
            ],
        );
    }

    public static function failure(string $message): JsonApiResponse
    {
        return new self(
            code: 500,
            body: [
                'status'  => 'failure',
                'data'    => null,
                'errors'  => null,
                'message' => $message,
            ],
        );
    }

    public static function forbidden(string|null $message = null): JsonApiResponse
    {
        return new self(
            code: 403,
            body: [
                'status'  => 'forbidden',
                'data'    => null,
                'errors'  => null,
                'message' => $message,
            ],
        );
    }

    public static function notFound(string $message): JsonApiResponse
    {
        return new self(
            code: 404,
            body: [
                'status'  => 'notFound',
                'data'    => null,
                'errors'  => null,
                'message' => $message,
            ],
        );
    }

    public static function success(array|null $data = null, string|null $message = null, int $code = 200): JsonApiResponse
    {
        assert($code >= 200 && $code < 300);

        return new self(
            code: $code,
            body: [
                'status'  => 'success',
                'data'    => $data,
                'errors'  => null,
                'message' => $message,
            ],
        );
    }

    public static function updated(string $message): JsonApiResponse
    {
        return new self(
            code: 200,
            body: [
                'status'  => 'success',
                'data'    => null,
                'errors'  => null,
                'message' => $message,
            ],
        );
    }
}

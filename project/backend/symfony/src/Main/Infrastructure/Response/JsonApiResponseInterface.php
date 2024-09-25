<?php

declare(strict_types=1);

namespace App\Main\Infrastructure\Response;

interface JsonApiResponseInterface extends ApiResponseInterface
{
    public static function created(string $message): JsonApiResponse;

    public static function deleted(string $message): JsonApiResponse;

    public static function error(array|null $errors = null, string|null $message = null, int $code = 400): JsonApiResponse;

    public static function notFound(string $message): JsonApiResponse;

    public static function success(array|null $data = null, string|null $message = null, int $code = 200): JsonApiResponse;

    public static function updated(string $message): JsonApiResponse;
}

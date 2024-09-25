<?php

declare(strict_types=1);

namespace App\Shared\Tools;

use function json_decode;
use function json_encode;
use function json_last_error;

class JsonTool
{
    /**
     * @throws \JsonException
     */
    function jsonDecode(string|bool $string): array|null
    {
        if (is_bool($string)) {
            return null;
        }

        if ($this->isJson($string)) {
            return json_decode($string, true, 512, JSON_THROW_ON_ERROR);
        }

        return null;
    }

    function jsonEncode(array $array): string|false
    {
        return json_encode($array, 512, JSON_THROW_ON_ERROR);
    }

    function isJson(string $string): bool
    {
        json_decode($string, true);

        return JSON_ERROR_NONE === json_last_error();
    }
}

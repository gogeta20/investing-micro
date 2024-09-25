<?php

declare(strict_types=1);

namespace App\Shared\Tools;
class ArrayTool
{
    static function arrayKeyExists(string $key, array $array): bool
    {
        return \array_key_exists($key, $array);
    }

    function inArray(mixed $needle, array $array): bool
    {
        return \in_array($needle, $array, true);
    }

    function isArray(mixed $element): bool
    {
        return is_array($element);
    }

    function firstItem(array $array = null): ?array
    {
        if (null === $array || [] === $array) {
            return null;
        }

        return $array[0];
    }

    function secondItem(array $array = null): ?array
    {
        if (null === $array || [] === $array) {
            return null;
        }

        return $array[1];
    }

    function lastItem(array $array = null): ?array
    {
        if (null === $array || [] === $array) {
            return null;
        }

        $index = count($array) - 1;

        return $array[$index];
    }

    function convertStringDQLToArray(string $string = null): ?array
    {
        if (null === $string || '' === $string) {
            return null;
        }
        $array = [];
        $elements = explode(';', $string);
        $i = -1;

        foreach ($elements as $element) {
            $subElement = explode('%&', $element);
            ++$i;
            foreach ($subElement as $fields) {
                $field = explode('|', $fields);
                $array[$i][$field[0]] = $field[1];
            }
        }

        return $array;
    }

    function recursiveKsort(array &$array): bool
    {
        foreach ($array as &$value) {
            if (is_array($value)) {
                recursiveKsort($value);
            }
        }

        return ksort($array);
    }

    function extractFromArray(array $array, array $keys): array
    {
        return array_filter($array, fn($key) => \in_array($key, $keys, true), ARRAY_FILTER_USE_KEY);
    }
}

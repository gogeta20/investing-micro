<?php

declare(strict_types=1);

namespace App\Shared\Tools;

class StringTool
{
    function adaptSpecialCharacters(string $text): string
    {
        return str_replace(
            search: ['&aacute;', '&eacute;', '&iacute;', '&oacute;', '&uacute;', '&ordm;', '&nbsp;', '&euro;'],
            replace: ['á', 'é', 'í', 'ó', 'ú', 'º', '', '€'],
            subject: $text
        );
    }

    function extractStringBetweenNeedles(string $mainString, string $beginningNeedle, string $endNeedle): string
    {
        $found = strpos(haystack: $mainString, needle: $beginningNeedle);

        if (false === $found) {
            return '';
        }

        $beginningIndex = strlen(string: $beginningNeedle) + $found;
        $end = strpos(haystack: $mainString, needle: $endNeedle, offset: $beginningIndex);

        $length = (false === $end ? 0 : $end) - $beginningIndex;

        return trim(substr(string: $mainString, offset: $beginningIndex, length: $length));
    }

    function getMonthStringES(string $month): ?string
    {
        $monthMap = [
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre',
        ];

        if (!array_key_exists($month, $monthMap)) {
            return null;
        }

        return $monthMap[$month];
    }

    function getWeekDayStringES(string $day): ?string
    {
        $dayMap = [
            '1' => 'Lunes',
            '2' => 'Martes',
            '3' => 'Miércoles',
            '4' => 'Jueves',
            '5' => 'Viernes',
            '6' => 'Sábado',
            '7' => 'Domingo',
        ];

        if (!array_key_exists($day, $dayMap)) {
            return null;
        }

        return $dayMap[$day];
    }
}

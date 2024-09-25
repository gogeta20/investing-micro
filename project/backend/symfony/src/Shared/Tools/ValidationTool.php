<?php

declare(strict_types=1);

namespace App\Shared\Tools;

class ValidationTool
{
    function isNullOrEmptyString(?string $string): bool
    {
        return null === $string || '' === $string;
    }

    function isNullOrEmptyArray(?array $array): bool
    {
        return null === $array || [] === $array;
    }

    function isValidDni(?string $value): bool
    {
        if (null === $value || 9 !== strlen(trim($value))) {
            return false;
        }

        $nif = strtoupper($value);

        $letter = $nif[strlen($nif) - 1];
        $number = substr($nif, 0, 8);

        $number = str_replace(['X', 'Y', 'Z'], [0, 1, 2], $number);

        $modulo = (int)$number % 23;
        $valid_letters = 'TRWAGMYFPDXBNJZSQVHLCKE';
        $valid_letter = $valid_letters[$modulo];

        return $valid_letter === $letter;
    }

    function isValidCif(string $cif): bool
    {
        $cif_codes = 'JABCDEFGHI';
        $sum = (string)cifSum($cif);
        $n = (10 - (int)substr($sum, -1)) % 10;

        if ((bool)preg_match('/^[ABCDEFGHJNPQRSUVW]{1}/', $cif)) {
            if (in_array($cif[0], ['A', 'B', 'E', 'H'], strict: true)) {
                // If digits start with 00 check digit should be a letter
                if ('0' === $cif[1] && '0' === $cif[2]) {
                    return $cif[8] === $cif_codes[$n];
                }

                // Numerico
                return (int)$cif[8] === $n;
            } elseif (in_array($cif[0], ['P', 'Q', 'S', 'N', 'W', 'R'], strict: true)) {
                /* If CIF number starts with P, Q, S, N, W or R, check digit should be a letter */
                // Letras
                return $cif[8] === $cif_codes[$n];
            } else {
                // If digits start with 00 check digit should be a letter
                if ('0' === $cif[1] && '0' === $cif[2]) {
                    return $cif[8] === $cif_codes[$n];
                }
                // Alfanumérico
                if (is_numeric($cif[8])) {
                    return (int)$cif[8] === $n;
                } else {
                    return $cif[8] === $cif_codes[$n];
                }
            }
        }

        return false;
    }

    function isSelfEmployed(string $cif): bool
    {
        return !isValidCif($cif);
    }

    function isValidMobile(string $mobileOrPhone): bool
    {
        return (bool)preg_match('/^[6-7][0-9]{8}$/', $mobileOrPhone);
    }

    function cifSum(string $cif): int
    {
        $sum = (int)$cif[2] + (int)$cif[4] + (int)$cif[6];

        for ($i = 1; $i < 8; $i += 2) {
            $tmp = (string)(2 * (int)$cif[$i]);

            $tmp = (int)$tmp[0] + ((2 === strlen($tmp)) ? (int)$tmp[1] : 0);

            $sum += $tmp;
        }

        return $sum;
    }
}

<?php

declare(strict_types=1);

namespace App\Shared\Tools;

class NumberTool
{
function numberFormat(float $value): string
{
    return number_format($value, 2, ',', '.');
}

function numberFormatCommaDecimal(float $value): string
{
    return number_format($value, 2, ',', '');
}

function numberFormatPointDecimal(float $value): string
{
    return number_format($value, 2, '.', '');
}

function numberFormatCirbe(float $value): string
{
    return str_replace('.', '', str_replace(',', '', number_format($value, 2)));
}

function taeFormatCirbe(float $value): string
{
    $int = (int) number_format((float) explode('.', (string) $value)[0], 0);

    return $int >= 100 ? '' : ($int >= 10 ? '0' : ($int > 0 ? '00' : '000')).explode('.', (string) $value)[0].explode('.', number_format($value, 3))[1];
}

function uidRandom(): int
{
    return generateRandom(size: 8);
}

function uidRandomNineDigits(): int
{
    return generateRandom(size: 9);
}

function generateRandom(int $size): int
{
    $number = null;
    for ($i = 0; $i < $size; ++$i) {
        $number .= random_int(1, 9);
    }

    return (int) $number;
}

function convertToPercent(float $fraction): float
{
    return (0 > $fraction || 1 < $fraction) ? 0 : round(num: $fraction * 100, precision: 2);
}

function percentageWithEightDecimals(float $dividend, float $divider): float
{
    return (0.01 >= $divider) ? 0 : round(num: $dividend / $divider * 100, precision: 8);
}

function lessOnehundred(float $value): bool
{
    if (100 < $value) {
        return false;
    }

    return true;
}

function moreTwoDecimals(float $value): bool
{
    $numDecimals = (int) strpos(strrev((string) $value), '.');
    if (2 < $numDecimals) {
        return true;
    }

    return false;
}
}

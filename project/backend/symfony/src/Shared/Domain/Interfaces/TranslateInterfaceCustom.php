<?php

namespace App\Shared\Domain\Interfaces;

interface TranslateInterfaceCustom
{
    public function translate(string $string, array $parameters = [], string $domain = null, string $locale = null): string;

    public function locale(?bool $capitalize = false): string;
}

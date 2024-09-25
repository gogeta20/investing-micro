<?php

declare(strict_types=1);

namespace App\Shared\Domain;


interface Parameters
{
    public function get(string $param): mixed;

    public function has(string $param): bool;

    public function all();
}

<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Pokemon\Get;

use App\Shared\Domain\Bus\Query\Query;

readonly class PokemonGetQuery implements Query
{
    public function __construct()
    {}
}

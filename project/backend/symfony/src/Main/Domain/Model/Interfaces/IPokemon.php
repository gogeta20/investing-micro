<?php

declare(strict_types=1);

namespace App\Main\Domain\Model\Interfaces;
use App\Main\Domain\Model\Pokemon;

interface IPokemon
{
    public function save(Pokemon $pokemon): void;
}

<?php
namespace App\Main\Domain\Repository\Interfaces;

interface IPokemonService
{
    public function getPokemonDetails(int $id): array;
    public function getPokemonSpecies(string $name): array;

}

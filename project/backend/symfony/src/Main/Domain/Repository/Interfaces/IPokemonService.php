<?php
namespace App\Main\Domain\Repository\Interfaces;

interface IPokemonService
{
    public function getPokemonDetails(string $name): array;
    public function getPokemonSpecies(string $name): array;

}

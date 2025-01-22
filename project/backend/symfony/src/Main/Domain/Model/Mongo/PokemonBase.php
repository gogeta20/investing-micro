<?php

namespace App\Main\Domain\Model\Mongo;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(collection: 'pokemon_base_view')]
class PokemonBase
{
    #[ODM\Id(strategy: 'AUTO')]
    private $id;

    #[ODM\Field(type: 'integer')]
    private int $numero_pokedex;

    #[ODM\Field(type: 'string')]
    private string $nombre;

    #[ODM\Field(type: 'float')]
    private float $peso;

    #[ODM\Field(type: 'float')]
    private float $altura;

    #[ODM\Field(type: 'float')]
    private float $ataque;

    #[ODM\Field(type: 'integer')]
    private float $defensa;

    #[ODM\Field(type: 'integer')]
    private float $velocidad;

    function getNombre(): string
    {
        return $this->nombre;
    }
    function getId(): int
    {
        return $this->id;
    }
    function getNumeroPokedex(): int
    {
        return $this->numero_pokedex;
    }
    function getPeso(): string
    {
        return $this->peso;
    }
    function getAtaque(): float
    {
        return $this->ataque;
    }
    function getDefensa(): int
    {
        return $this->defensa;
    }
    function getVelocidad(): int
    {
        return $this->velocidad;
    }

    function getAltura(): float
    {
        return $this->altura;
    }
}

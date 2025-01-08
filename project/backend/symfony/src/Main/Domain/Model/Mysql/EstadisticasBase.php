<?php

namespace App\Main\Domain\Model\Mysql;

use App\Main\Domain\Model\Events\EstadisticasBaseCreateDomainEvent;
use App\Shared\Domain\Aggregate\AggregateRoot;
use Ramsey\Uuid\Uuid;

class EstadisticasBase extends AggregateRoot
{
    private int $numero_pokedex;
    private float $ps;
    private float $ataque;
    private float $defensa;
    private float $velocidad;

    public function __construct(int $numero_pokedex, float $ps, float $ataque, float $defensa, float $velocidad)
    {
        $this->numero_pokedex = $numero_pokedex;
        $this->ps = $ps;
        $this->ataque = $ataque;
        $this->defensa = $defensa;
        $this->velocidad = $velocidad;
    }

    public static function create(
        int $numero_pokedex,
        float $ps,
        float $ataque,
        float $defensa,
        float $velocidad
    ): void
    {
        $pokemon = new self($numero_pokedex, $ps, $ataque, $defensa, $velocidad);
        $pokemon->record(new EstadisticasBaseCreateDomainEvent(Uuid::uuid4(),$numero_pokedex, $ps, $ataque, $defensa, $velocidad));
    }

    public function getNumeroPokedex(): int
    {
        return $this->numero_pokedex;
    }
    public function getPs(): float
    {
        return $this->ps;
    }
    public function getAtaque(): float
    {
        return $this->ataque;
    }
    public function getDefensa(): float
    {
        return $this->defensa;
    }
    public function getVelocidad(): float
    {
        return $this->velocidad;
    }

}

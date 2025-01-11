<?php

namespace App\Main\Domain\Model;

use App\Main\Domain\Model\Events\EstadisticasBaseCreateDomainEvent;
use App\Shared\Domain\Aggregate\AggregateRoot;
use Ramsey\Uuid\Uuid;

class EstadisticasBase extends AggregateRoot
{
    private int $numero_pokedex;
    private float $ps;
    private float $ataque;
    private float $defensa;
    private float $especial;
    private float $velocidad;

    public function __construct(int $numero_pokedex, float $ps, float $ataque, float $defensa, float $especial,float $velocidad)
    {
        $this->numero_pokedex = $numero_pokedex;
        $this->ps = $ps;
        $this->ataque = $ataque;
        $this->defensa = $defensa;
        $this->especial = $especial;
        $this->velocidad = $velocidad;
    }

    public static function create(
        int $numero_pokedex,
        float $ps,
        float $ataque,
        float $defensa,
        float $especial,
        float $velocidad
    ): EstadisticasBase
    {
        $eb = new self($numero_pokedex, $ps, $ataque, $defensa,$especial, $velocidad);
        $eb->record(new EstadisticasBaseCreateDomainEvent(Uuid::uuid4(),$numero_pokedex, $ps, $ataque, $defensa, $especial,$velocidad));
        return $eb;
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
    public function getEspecial(): float
    {
        return $this->especial;
    }

    public function getVelocidad(): float
    {
        return $this->velocidad;
    }

    public function update(float $ps, float $ataque, float $defensa, float $especial, float $velocidad): void
    {
        $this->ps = $ps;
        $this->ataque = $ataque;
        $this->defensa = $defensa;
        $this->especial = $especial;
        $this->velocidad = $velocidad;
    }

}

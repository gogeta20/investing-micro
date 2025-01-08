<?php
namespace App\Main\Domain\Model;

use App\Main\Domain\Model\Events\PokemonCreateDomainEvent;
use App\Main\Domain\Model\Mysql\EstadisticasBase;
use App\Shared\Domain\Aggregate\AggregateRoot;
use Ramsey\Uuid\Uuid;

class Pokemon extends AggregateRoot
{
    private int $numero_pokedex;
    private string $nombre;
    private float $peso;
    private float $altura;
    private EstadisticasBase $estadisticasBase;

//    public function __construct(string $numero_pokedex, string $nombre, float $peso, float $altura, EstadisticasBase $estadisticasBase)
    public function __construct(string $numero_pokedex, string $nombre, float $peso, float $altura)
    {
        $this->numero_pokedex = $numero_pokedex;
        $this->nombre = $nombre;
        $this->peso = $peso;
        $this->altura = $altura;
//        $this->estadisticasBase = $estadisticasBase;
    }

    public static function create(
        int $numero_pokedex,
        string $nombre,
        float $peso,
        float $altura,
//        EstadisticasBase $estadisticasBase
    ): Pokemon
    {
//        $pokemon = new self($numero_pokedex, $nombre, $peso, $altura, $estadisticasBase);
        $pokemon = new self($numero_pokedex, $nombre, $peso, $altura);
        $pokemon->record(new PokemonCreateDomainEvent(
            Uuid::uuid4(),
            [
                $numero_pokedex,
                $nombre,
                $peso,
                $altura
            ],
            'PokemonCreateDomainEvent'
        ));
        return $pokemon;
    }

    public function getNumeroPokedex(): int
    {
        return $this->numero_pokedex;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getPeso(): float
    {
        return $this->peso;
    }

    public function getAltura(): float
    {
        return $this->altura;
    }

//    public function getEstadisticasBase(): EstadisticasBase
//    {
//        return $this->estadisticasBase;
//    }
}


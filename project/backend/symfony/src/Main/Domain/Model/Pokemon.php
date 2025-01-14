<?php
namespace App\Main\Domain\Model;

use App\Main\Domain\Model\Events\PokemonCreateDomainEvent;
use App\Shared\Domain\Aggregate\AggregateRoot;
use Ramsey\Uuid\Uuid;

class Pokemon extends AggregateRoot
{
    private ?int $numero_pokedex;
    private string $nombre;
    private float $peso;
    private float $altura;
    private ?EstadisticasBase $estadisticasBase;

    public function __construct(?int $numero_pokedex, string $nombre, float $peso, float $altura)
    {
        $this->numero_pokedex = $numero_pokedex;
        $this->nombre = $nombre;
        $this->peso = $peso;
        $this->altura = $altura;
    }

    public static function create(
        ?int $numero_pokedex,
        string $nombre,
        float $peso,
        float $altura,
    ): Pokemon
    {
        $pokemon = new self($numero_pokedex, $nombre, $peso, $altura);


        return $pokemon;
    }

    public function updateEstadisticasBase(
        int $ps,
        int $ataque,
        int $defensa,
        int $velocidad
    ): void {
        $this->estadisticasBase->update($ps, $ataque, $defensa, $velocidad);
        $this->record(new PokemonCreateDomainEvent(
            Uuid::uuid4(),
            [
                'numero_pokedex' => $this->numero_pokedex,
                'nombre' => $this->nombre,
                'peso' => $this->peso,
                'altura' => $this->altura,
                'estadisticas' => [
                    'ps' => $ps,
                    'ataque' => $ataque,
                    'defensa' => $defensa,
                    'velocidad' => $velocidad,
                ]
            ],
            'PokemonStatsUpdated'
        ));
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

    public function getEstadisticasBase(): EstadisticasBase
    {
        return $this->estadisticasBase;
    }

    public function setEstadisticasBase(EstadisticasBase $estadisticasBase): void
    {
        $this->record(new PokemonCreateDomainEvent(
            Uuid::uuid4(),
            [
                "numero_pokedex" => $this->numero_pokedex,
                "nombre" => $this->nombre,
                "peso" => $this->peso,
                "altura" => $this->altura,
                'estadisticas' => [
                    'ps' => $estadisticasBase->getPs(),
                    'ataque' => $estadisticasBase->getAtaque(),
                    'defensa' => $estadisticasBase->getDefensa(),
                    'velocidad' => $estadisticasBase->getVelocidad(),
                ]
            ],
            'PokemonCreate'
        ));
        $this->estadisticasBase = $estadisticasBase;
    }

}


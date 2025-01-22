<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Pokemon\Create;

use App\Shared\Domain\Bus\Command\Command;

readonly class PokemonCreateCommand implements Command
{
    public function __construct(
        private string $nombre,
        private int $ataque,
        private int $defensa,
        private int $velocidad,
        private int $especial,
        private int $peso,
        private int $altura,
        private int $ps,
    ) {}

    public static function create(array $data): self
    {
        return new self(
            $data['nombre'],
            $data['ataque'],
            $data['defensa'],
            $data['velocidad'],
            $data['especial'],
            $data['peso'],
            $data['altura'],
            $data['ps'],
        );
    }

    public function nombre(): string
    {
        return $this->nombre;
    }

    public function ataque(): int
    {
        return $this->ataque;
    }

    public function defensa(): int
    {
        return $this->defensa;
    }

    public function velocidad(): int
    {
        return $this->velocidad;
    }

    public function hp(): int
    {
        return $this->hp;
    }

    public function especial(): int
    {
        return $this->especial;
    }
    public function altura(): int
    {
        return $this->altura;
    }
    public function peso(): int
    {
        return $this->peso;
    }
    public function ps(): int
    {
        return $this->ps;
    }

}

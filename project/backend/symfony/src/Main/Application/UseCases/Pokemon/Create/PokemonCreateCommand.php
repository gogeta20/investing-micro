<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Pokemon\Create;

use App\Shared\Domain\Bus\Command\Command;

readonly class PokemonCreateCommand implements Command
{
    public function __construct(
        private int $numeroPokedex,
        private string $nombre,
        private int $ataque,
        private int $defensa,
        private int $velocidad,
        private int $hp,
        private int $especial,
    ) {}

    public static function create(array $data): self
    {
        return new self(
            $data['numeroPokedex'],
            $data['nombre'],
            $data['ataque'],
            $data['defensa'],
            $data['velocidad'],
            $data['hp'],
            $data['especial']
        );
    }
    public function numeroPokedex(): int
    {
        return $this->numeroPokedex;
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

}

<?php
declare(strict_types=1);
namespace App\Main\Infrastructure\Storage;

use App\Main\Domain\Model\Interfaces\IPokemon;
use App\Main\Domain\Model\Pokemon;
use App\Shared\Infrastructure\BaseRepository;

final class PokemonRepository extends BaseRepository implements IPokemon
{
    public function save(Pokemon $pokemon): void
    {
        $estadisticasBase = $pokemon->getEstadisticasBase();
        $this->persist($pokemon);
        $this->persist($estadisticasBase);
    }
}

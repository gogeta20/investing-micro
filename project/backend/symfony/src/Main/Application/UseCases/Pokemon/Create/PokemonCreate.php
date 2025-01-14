<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Pokemon\Create;

use App\Main\Domain\Exception\StoreException;
use App\Main\Domain\Model\EstadisticasBase;
use App\Main\Domain\Model\Interfaces\IPokemon;
use App\Main\Domain\Model\Pokemon;
use App\Shared\Domain\Bus\Event\EventBus;

readonly class PokemonCreate
{
    public function __construct(
        private EventBus $bus,
        private IPokemon $IPokemon,
    ) {}

    /**
     * @throws StoreException
     */
    public function __invoke(PokemonCreateCommand $command): void
    {
        try {
            $pokemon = Pokemon::create(235,'mauriciomendoza', 20, 80);
            $estadisticasBase = EstadisticasBase::create($pokemon->getNumeroPokedex(),20,20,20,20, 21);
            $pokemon->setEstadisticasBase($estadisticasBase);
            $this->IPokemon->save($pokemon);
            $this->bus->publish(...$pokemon->pullDomainEvents());
        } catch (\Exception $e) {
            throw new StoreException('Error al crear el artículo: ' . $e->getMessage());
        }
    }
}

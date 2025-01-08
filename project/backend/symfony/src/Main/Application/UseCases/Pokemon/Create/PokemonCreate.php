<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Pokemon\Create;

use App\Main\Domain\Event\PokemonDetailsRetrievedEvent;
use App\Main\Domain\Exception\StoreException;
use App\Main\Domain\Model\Interfaces\IPokemon;
use App\Main\Domain\Model\Mysql\EstadisticasBase;
use App\Main\Domain\Model\Pokemon;
use App\Shared\Domain\Bus\Event\EventBus;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class PokemonCreate
{
    public function __construct(
        private EventBus $bus,
        private IPokemon $IPokemonRepository,
    ) {}

    /**
     * @throws StoreException
     */
    public function __invoke(PokemonCreateCommand $command): void
    {
        try {
            $estadistasBase = new EstadisticasBase(158,20,20,20,20);
            $pokemon = Pokemon::create(158,'mauricio', 20, 80,$estadistasBase);
            $this->bus->publish(...$pokemon->pullDomainEvents());
        } catch (\Exception $e) {
            throw new StoreException('Error al crear el artículo: ' . $e->getMessage());
        }
    }
}

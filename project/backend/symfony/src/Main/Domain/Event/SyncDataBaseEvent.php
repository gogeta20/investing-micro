<?php

namespace App\Main\Domain\Event;

use App\Shared\Domain\Bus\Event\DomainEvent;

class SyncDataBaseEvent extends DomainEvent
{
    private array $pokemonData;

    public function __construct(string $aggregateId, array $pokemonData, string $eventId = null, string $occurredOn = null)
    {
        parent::__construct($aggregateId, $eventId, $occurredOn);
        $this->pokemonData = $pokemonData;
    }

    public function getPokemonData(): array
    {
        return $this->pokemonData;
    }

    public static function eventName(): string
    {
        return 'pokemon.details.retrieved';
    }

    public static function fromPrimitives(string $aggregateId, array $body, string $eventId, string $occurredOn): self
    {
        return new self($aggregateId, $body['pokemonData'], $eventId, $occurredOn);
    }

    public function toPrimitives(): array
    {
        return [
            'pokemonData' => $this->pokemonData
        ];
    }
}


<?php

declare(strict_types=1);
namespace App\Main\Domain\Model\Events;

use App\Shared\Domain\Bus\Event\DomainEvent;

final class PokemonCreateDomainEvent extends DomainEvent
{
    private array $data;
    private ?string $eventId;
    public function __construct(string $aggregateId, array $data, string $eventId = null, string $occurredOn = null)
    {
        parent::__construct($aggregateId, $eventId, $occurredOn);
        $this->data = $data;
        $this->eventId = $eventId;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getEventId(): ?string
    {
        return $this->eventId;
    }

    public static function eventName(): string
    {
        return 'pokemon.created';
    }

    public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $eventId,
        string $occurredOn
    ): self {
        return new self(
            $aggregateId,
            $body['data'],
            $eventId,
            $occurredOn
        );
    }

    public function toPrimitives(): array
    {
        return [
            'data' => $this->data
        ];
    }
}

<?php

declare(strict_types=1);
namespace App\Main\Domain\Model\Events;

use App\Shared\Domain\Bus\Event\DomainEvent;

final class EstadisticasBaseCreateDomainEvent extends DomainEvent
{
    public function __construct(
        string                 $id,
        private readonly ?int  $numero_pokedex,
        private readonly float $ps,
        private readonly float $ataque,
        private readonly float $velocidad,
        string                  $eventId = null,
        string                  $occurredOn = null
    ) {
        parent::__construct($id, $eventId, $occurredOn);
    }

    public static function eventName(): string
    {
        return 'estadisticasbase.created';
    }

    public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $eventId,
        string $occurredOn
    ): self {
        return new self(
            $aggregateId,
            $body['numero_pokedex'],
            $body['ps'],
            $body['ataque'],
            $body['velocidad'],
            $eventId,
            $occurredOn
        );
    }

    public function toPrimitives(): array
    {
        return [
            'numero_pokedex' => $this->numero_pokedex,
            'ps' => $this->ps,
            'ataque' => $this->ataque,
            'velocidad' => $this->velocidad,
        ];
    }
}

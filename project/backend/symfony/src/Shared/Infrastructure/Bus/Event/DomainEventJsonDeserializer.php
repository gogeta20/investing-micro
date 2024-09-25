<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Event;

use App\Shared\Domain\AppException;
use App\Shared\Domain\Bus\Event\DomainEvent;
use App\Shared\Domain\Utils;
use JsonException;

final class DomainEventJsonDeserializer
{
    public function __construct(private DomainEventMapping $mapping)
    {
    }

    /**
     * @throws JsonException|AppException
     */
    public function deserialize(string $domainEvent): DomainEvent
    {
        $eventData = Utils::jsonDecode($domainEvent);
        $eventName = $eventData['data']['type'];
        $eventClass = $this->mapping->for($eventName);
        if (null === $eventClass) {
            throw new AppException("The event <$eventName> doesn't exist or has no subscribers");
        }
        return $eventClass::fromPrimitives(
            $eventData['data']['attributes']['id'],
            $eventData['data']['attributes'],
            $eventData['data']['id'],
            $eventData['data']['occurred_on']
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Event;

use App\Shared\Domain\Bus\Event\DomainEventSubscriber;
use App\Shared\Infrastructure\Bus\CallableFirstParameterExtractor;
use RuntimeException;
use Traversable;

use function Lambdish\Phunctional\search;

final class DomainEventSubscriberLocator
{
    private array $mapping;

    public function __construct(Traversable $mapping)
    {
        $this->mapping = iterator_to_array($mapping);
    }

    public function allSubscribedTo(string $eventClass): array
    {
        $formatted = CallableFirstParameterExtractor::forPipedCallables($this->mapping);
        return $formatted[$eventClass];
    }

    public function all(): array
    {
        return $this->mapping;
    }
}

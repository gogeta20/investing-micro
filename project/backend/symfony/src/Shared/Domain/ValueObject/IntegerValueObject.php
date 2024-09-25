<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

class IntegerValueObject
{
    public function __construct(protected int $value)
    {
    }

    public function __toString(): string
    {
        return (string)$this->value();
    }

    public function value(): int
    {
        return $this->value;
    }
}

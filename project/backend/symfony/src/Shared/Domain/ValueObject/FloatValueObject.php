<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

class FloatValueObject
{
    public function __construct(protected float $value)
    {
    }

    public function __toString(): string
    {
        return (string)$this->value();
    }

    public function value(): float
    {
        return $this->value;
    }
}

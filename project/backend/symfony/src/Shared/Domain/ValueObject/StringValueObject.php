<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\Utils;

class StringValueObject
{

    public function __construct(protected string $value)
    {
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString()
    {
        return $this->value();
    }

    public function toArray(): array
    {
        return Utils::jsonDecode($this->value());
    }
}

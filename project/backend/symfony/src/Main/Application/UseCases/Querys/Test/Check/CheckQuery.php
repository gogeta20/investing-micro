<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Querys\Test\Check;

use App\Shared\Domain\Bus\Query\Query;

class CheckQuery implements Query
{
    public function __construct(
        private readonly ?string $email = null,
    )
    {}

    public static function create(array $data): self
    {
        return new self(
            $data['email'] ?? null,
        );
    }

    public function email(): ?string
    {
        return $this->email;
    }
}

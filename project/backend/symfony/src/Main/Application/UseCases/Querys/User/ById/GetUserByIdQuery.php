<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Querys\User\ById;

use App\Shared\Domain\Bus\Query\Query;

final readonly class GetUserByIdQuery implements Query
{
    public function __construct(
        private string $user
    ) {}

    public function user(): string
    {
        return $this->user;
    }
}

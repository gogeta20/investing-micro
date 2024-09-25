<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Querys\Article\ById;

use App\Shared\Domain\Bus\Query\Query;

final readonly class GetArticleByIdQuery implements Query
{
    public function __construct(
        private string $id
    ) {}

    public function id(): string
    {
        return $this->id;
    }
}

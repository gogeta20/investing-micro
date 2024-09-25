<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Querys\Article\All;

use App\Shared\Domain\Bus\Query\Query;

final class GetAllArticlesQuery implements Query
{
    private string $userId;
    private ?string $all = null;

    public function __construct(string $userId, ?string $all)
    {
        $this->userId = $userId;
        $this->all = $all;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function all(): ?string
    {
        return $this->all;
    }
}

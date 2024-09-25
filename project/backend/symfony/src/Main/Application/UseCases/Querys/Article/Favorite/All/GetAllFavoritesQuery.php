<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Querys\Article\Favorite\All;

use App\Shared\Domain\Bus\Query\Query;

final class GetAllFavoritesQuery implements Query
{
    private string $userId;

    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }

    public static function create(array $data): self
    {
        return new self($data['userId']);
    }

    public function userId(): string
    {
        return $this->userId;
    }
}

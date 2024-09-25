<?php

declare(strict_types=1);

namespace App\Shared\Domain;

class BaseCriteria
{
    public function __construct(
        private readonly array $filters,
        private readonly ?string $orderBy = null,
        private readonly ?string $order = null,
        private readonly ?int $limit = null,
        private readonly ?int $offset = null
    ) {
    }

    public function filters(): array
    {
        return $this->filters;
    }

    public function orderBy(): ?string
    {
        return $this->orderBy;
    }

    public function order(): ?string
    {
        return $this->order;
    }

    public function limit(): ?int
    {
        return $this->limit;
    }

    public function offset(): ?int
    {
        return $this->offset;
    }
}

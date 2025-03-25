<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Payments\Paypal\Approve;

use App\Shared\Domain\Bus\Query\Query;

readonly class PaymentApprovePaypalQuery implements Query
{
    public function __construct(private string $id) {}

    public static function create(array $data): self
    {
        return new self(
            $data['id'],
        );
    }

    public function id(): string
    {
        return $this->id;
    }
}

<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Payments\Paypal\Confirm;

use App\Shared\Domain\Bus\Query\Query;

readonly class PaymentPaypalConfirmQuery implements Query
{
    public function __construct(
        private string $token,
    ) {}

    public static function create(array $data): self
    {
        return new self(
            $data['token'],
        );
    }

    public function token(): string
    {
        return $this->token;
    }
}

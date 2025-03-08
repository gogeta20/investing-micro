<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Payments\Paypal;

use App\Shared\Domain\Bus\Command\Command;

readonly class PaymentPaypalCommand implements Command
{
    public function __construct(
        private float $amount,
        private string $currency,
    ) {}

    public static function create(array $data): self
    {
        return new self(
            $data['amount'],
            $data['currency'],
        );
    }

    public function amount(): float
    {
        return $this->amount;
    }

    public function currency(): string
    {
        return $this->currency;
    }
}

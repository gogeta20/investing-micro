<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Payments\Paypal\Confirm;

use App\Shared\Domain\Bus\Command\Command;

readonly class PaymentPaypalConfirmCommand implements Command
{
    public function __construct(
        private string $paymentId,
        private string $payerId
    ) {}

    public static function create(array $data): self
    {
        return new self(
            $data['paymentId'],
            $data['payerId']
        );
    }

    public function paymentId(): string
    {
        return $this->paymentId;
    }

    public function payerId(): string
    {
        return $this->payerId;
    }
}

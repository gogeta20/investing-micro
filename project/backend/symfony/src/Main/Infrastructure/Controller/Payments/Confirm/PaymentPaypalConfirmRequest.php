<?php

declare(strict_types=1);

namespace App\Main\Infrastructure\Controller\Payments\Confirm;

use App\Main\Infrastructure\Request\StandardRequest;
use Symfony\Component\Validator\Constraints as Assert;

class PaymentPaypalConfirmRequest extends StandardRequest
{
    protected function constraints(): Assert\Collection
    {
        return new Assert\Collection([
            'fields' => [
                'token' => [
                    new Assert\NotBlank(),
                    new Assert\Type(['type' => 'string']),
                ],
            ],
        ]);
    }
}

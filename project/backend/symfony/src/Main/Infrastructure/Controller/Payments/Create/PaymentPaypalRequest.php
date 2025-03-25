<?php

declare(strict_types=1);

namespace App\Main\Infrastructure\Controller\Payments\Create;

use App\Main\Infrastructure\Request\StandardRequest;
use Symfony\Component\Validator\Constraints as Assert;

class PaymentPaypalRequest extends StandardRequest
{
    protected function constraints(): Assert\Collection
    {
        return new Assert\Collection([
            'fields' => [
                'id' => [
                    new Assert\NotBlank(),
                ],
                'amount' => [
                    new Assert\NotBlank(),
                    new Assert\Type(['type' => 'numeric', 'message' => 'The amount must be a valid number.']),
                    new Assert\GreaterThan(['value' => 0, 'message' => 'The amount must be greater than zero.']),
                ],
                'currency' => [
                    new Assert\NotBlank(),
                    new Assert\Choice([
                        'choices' => ['USD', 'EUR', 'GBP'],
                        'message' => 'Invalid currency. Allowed values: USD, EUR, GBP.',
                    ]),
                ],
                'description' => [
                    new Assert\NotBlank(),
                    new Assert\Type(['type' => 'string']),
                    new Assert\Length(['max' => 255, 'maxMessage' => 'The description cannot be longer than 255 characters.']),
                ],
                'customer_email' => [
                    new Assert\Optional(),
                    new Assert\Email(['message' => 'The email is not a valid email address.']),
                ],
            ],
        ]);
    }
}

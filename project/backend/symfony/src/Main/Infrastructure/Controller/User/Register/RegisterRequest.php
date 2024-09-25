<?php
declare(strict_types=1);

namespace App\Main\Infrastructure\Controller\User\Register;

use App\Main\Infrastructure\Request\StandardRequest;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterRequest extends StandardRequest
{
    protected function constraints(): Assert\Collection
    {
        return new Assert\Collection([
            'fields' => [
                'email' => [
                    new Assert\NotBlank(),
                    new Assert\Email(),
                ],
                'password' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 6]),
                ],
            ],
        ]);
    }
}

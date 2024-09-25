<?php
declare(strict_types=1);

namespace App\Main\Infrastructure\Controller\User\Edit;

use App\Main\Infrastructure\Request\StandardRequest;
use Symfony\Component\Validator\Constraints as Assert;

class EditUserRequest extends StandardRequest
{
    protected function constraints(): Assert\Collection
    {
        return new Assert\Collection([
            'fields' => [
                'new_email' => new Assert\Optional([
                    new Assert\NotBlank(),
                    new Assert\Email(),
                ]),
                'username' => new Assert\Optional([
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 3, 'max' => 50]),
                ]),
                'password' => new Assert\Optional([
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 6]),
                ]),
            ],
        ]);
    }
}

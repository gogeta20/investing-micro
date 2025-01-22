<?php

declare(strict_types=1);

namespace App\Main\Infrastructure\Controller\Pokemon\Post;

use App\Main\Infrastructure\Request\StandardRequest;
use Symfony\Component\Validator\Constraints as Assert;

class CreatePokemonRequest extends StandardRequest
{
    protected function constraints(): Assert\Collection
    {
        return new Assert\Collection([
            'fields' => [
                'nombre' => [
                    new Assert\NotBlank(),
                ],
                'ataque' => [
                    new Assert\NotBlank(),
                ],
                'defensa' => [
                    new Assert\NotBlank(),
                ],
                'velocidad' => [
                    new Assert\NotBlank(),
                ],
                'especial' => [
                    new Assert\NotBlank(),
                ],
                'peso' => [
                    new Assert\NotBlank(),
                ],
                'altura' => [
                    new Assert\NotBlank(),
                ],
                'ps' => [
                    new Assert\NotBlank(),
                ],
            ],
        ]);
    }
}

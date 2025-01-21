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
                'numeroPokedex' => [
                    new Assert\NotBlank(),
                ],
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
                'hp' => [
                    new Assert\NotBlank(),
                ],
                'especial' => [
                    new Assert\NotBlank(),
                ],
            ],
        ]);
    }
}

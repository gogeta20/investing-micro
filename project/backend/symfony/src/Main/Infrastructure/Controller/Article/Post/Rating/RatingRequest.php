<?php

declare(strict_types=1);

namespace App\Main\Infrastructure\Controller\Article\Post\Rating;

use App\Main\Infrastructure\Request\StandardRequest;
use Symfony\Component\Validator\Constraints as Assert;

class RatingRequest extends StandardRequest
{
    protected function constraints(): Assert\Collection
    {
        return new Assert\Collection([
            'fields' => [
                'id' => [
                    new Assert\NotBlank(),
                    new Assert\Uuid(), // Asegura que el articleId sea un UUID válido
                ],
                'rating' => [
                    new Assert\NotBlank(),
                    new Assert\Range([
                        'min' => 1,
                        'max' => 5,
                        'notInRangeMessage' => 'La calificación debe estar entre 1 y 5.',
                    ]),
                ],
                'comment' => [
                    new Assert\Optional([
                        new Assert\Length([
                            'max' => 500,
                            'maxMessage' => 'El comentario no debe exceder los 500 caracteres.',
                        ]),
                    ]),
                ],
            ],
        ]);
    }
}

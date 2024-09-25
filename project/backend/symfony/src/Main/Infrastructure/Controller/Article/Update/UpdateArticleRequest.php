<?php

declare(strict_types=1);

namespace App\Main\Infrastructure\Controller\Article\Update;

use Symfony\Component\Validator\Constraints as Assert;
use App\Main\Infrastructure\Request\StandardRequest;


class UpdateArticleRequest extends StandardRequest
{
    protected function constraints(): Assert\Collection
    {
        return new Assert\Collection([
            'fields' => [
                'id' => [
                        new Assert\NotBlank(),
                ],
                'title' => [
                    new Assert\Optional([
                        new Assert\Length(['min' => 3, 'max' => 255]),
                        new Assert\NotBlank(),
                    ]),
                ],
                'body' => [
                    new Assert\Optional([
                        new Assert\NotBlank(),
                    ]),
                ],
                'mediaFile' => [
                    new Assert\Optional([
                        new Assert\File([
                            'maxSize' => '5M',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png',
                                'image/gif',
                                'video/mp4',
                            ],
                            'mimeTypesMessage' => 'Por favor, sube un archivo válido (JPEG, PNG, GIF, MP4).',
                        ]),
                    ]),
                ],
                'files' => [
                    new Assert\Optional([
                        new Assert\All([
                            new Assert\File([
                                'maxSize'   => '20M',
                                'mimeTypes' => ['image/jpeg', 'image/png', 'text/plain', 'application/pdf'],
                            ]),
                        ]),
                    ]),
                ],
            ],
        ]);
    }
}

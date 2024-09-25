<?php

declare(strict_types=1);

namespace App\Main\Infrastructure\Controller\Article\Post\Favorite;

use App\Main\Infrastructure\Request\StandardRequest;
use Symfony\Component\Validator\Constraints as Assert;

class FavoriteRequest extends StandardRequest
{
    protected function constraints(): Assert\Collection
    {
        return new Assert\Collection([
            'fields' => [
                'id' => [
                    new Assert\NotBlank(),
                    new Assert\Uuid(),
                ],
            ],
        ]);
    }
}

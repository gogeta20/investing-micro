<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Command\Article\Favorite\Create;

use App\Shared\Domain\Bus\Command\Command;

readonly class FavoriteCommand implements Command
{
    public function __construct(
        private string $email,
        private string $articleId
    ) {}

    public static function create(array $data): self
    {
        return new self(
            $data['email'],
            $data['articleId']
        );
    }

    public function email(): string
    {
        return $this->email;
    }

    public function articleId(): string
    {
        return $this->articleId;
    }
}

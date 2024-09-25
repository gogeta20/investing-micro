<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Command\Article\Rating\Create;

use App\Shared\Domain\Bus\Command\Command;

readonly class CreateRatingCommand implements Command
{
    public function __construct(
        private string $email,
        private string $articleId,
        private int $rating,
        private ?string $comment = null
    ) {}

    public static function create(array $data): self
    {
        return new self(
            $data['email'],
            $data['articleId'],
            $data['rating'],
            $data['comment'] ?? null
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

    public function rating(): int
    {
        return $this->rating;
    }

    public function comment(): ?string
    {
        return $this->comment;
    }
}

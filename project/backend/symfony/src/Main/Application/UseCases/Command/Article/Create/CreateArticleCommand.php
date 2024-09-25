<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Command\Article\Create;

use App\Shared\Domain\Bus\Command\Command;

readonly class CreateArticleCommand implements Command
{
    public function __construct(
        private string $email,
        private string $title,
        private string $body,
        private ?string $mediaUrl = null,
        private ?string $mediaType = null
    ) {}

    public static function create(array $data): self
    {
        return new self(
            $data['email'],
            $data['title'],
            $data['body'],
            $data['mediaUrl'] ?? null,
            $data['mediaType'] ?? null
        );
    }

    public function email(): string
    {
        return $this->email;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function body(): string
    {
        return $this->body;
    }

    public function mediaUrl(): ?string
    {
        return $this->mediaUrl;
    }

    public function mediaType(): ?string
    {
        return $this->mediaType;
    }
}

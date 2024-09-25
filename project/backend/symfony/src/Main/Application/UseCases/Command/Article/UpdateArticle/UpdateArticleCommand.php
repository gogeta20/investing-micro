<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Command\Article\UpdateArticle;

use App\Shared\Domain\Bus\Command\Command;

final class UpdateArticleCommand implements Command
{
    private ?string $email;
    private ?string $title;
    private ?string $body;
    private ?string $mediaUrl;
    private ?string $mediaType;
    private string $id;

    public function __construct(
        string $email,
        string $id,
        ?string $title = null,
        ?string $body = null,
        ?string $mediaUrl = null,
        ?string $mediaType = null
    ) {
        $this->email = $email;
        $this->id = $id;
        $this->title = $title;
        $this->body = $body;
        $this->mediaUrl = $mediaUrl;
        $this->mediaType = $mediaType;
    }

    public static function create(array $data): self
    {
        if ($data['mediaUrl']){
            $url = str_replace('public/', '', $data['mediaUrl']);
        }else{
            $url = null;
        }
        return new self(
            $data['email'],
            $data['id'],
            $data['title'] ?? null,
            $data['body'] ?? null,
            $url,
            $data['mediaType'] ?? null
        );
    }

    public function email(): string
    {
        return $this->email;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function title(): ?string
    {
        return $this->title;
    }

    public function body(): ?string
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

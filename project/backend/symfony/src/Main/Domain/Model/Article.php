<?php

namespace App\Main\Domain\Model;

use InvalidArgumentException;

class Article
{
    private string $id;
    private string $title;
    private string $body;
    private ?string $mediaUrl;
    private ?string $mediaType;
    private ?User $author;

    public function __construct(string $id, string $title, string $body, ?string $mediaUrl = null, ?string $mediaType = null, ?User $author = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->body = $body;
        $this->mediaUrl = $mediaUrl;
        $this->mediaType = $mediaType;
        $this->author = $author;
    }

    public static function verifyData(array $data): self
    {
        if (!isset($data['id'], $data['title'], $data['body'])) {
            throw new InvalidArgumentException('Invalid data structure: id, title, and body are required');
        }

        return new self(
            $data['id'],
            $data['title'],
            $data['body'],
            $data['mediaUrl'] ?? null,
            $data['mediaType'] ?? null,
            $data['author'] ?? null
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getMediaUrl(): ?string
    {
        return $this->mediaUrl;
    }

    public function getMediaType(): ?string
    {
        return $this->mediaType;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function setMediaUrl(?string $mediaUrl): void
    {
        $this->mediaUrl = $mediaUrl;
    }

    public function setMediaType(?string $mediaType): void
    {
        $this->mediaType = $mediaType;
    }

    public function setAuthor(?User $author): void
    {
        $this->author = $author;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'mediaUrl' => "http://localhost:8080/" . $this->mediaUrl,
            'mediaType' => $this->mediaType,
            'author' => $this->author?->getId(),
            'email' => $this->author?->getEmail(),
        ];
    }
}

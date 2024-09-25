<?php

namespace App\Main\Domain\Model;

class Favorite
{
    private string $id;
    private User $user;
    private Article $article;
    private \DateTimeImmutable $createdAt;

    public function __construct(string $id, User $user, Article $article)
    {
        $this->id = $id;
        $this->user = $user;
        $this->article = $article;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getArticle(): Article
    {
        return $this->article;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user' => [
                'id' => $this->user->getId(),
                'email' => $this->user->getEmail(),
            ],
            'article' => [
                'id' => $this->article->getId(),
                'title' => $this->article->getTitle(),
                'body' => $this->article->getBody(),
                'mediaUrl' => $this->article->getMediaUrl(),
                'email' => $this->article->getAuthor()->getEmail()
            ],
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}

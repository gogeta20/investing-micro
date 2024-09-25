<?php

namespace App\Main\Domain\Model;

class Rating
{
    private string $id;
    private int $rating;
    private ?string $comment;
    private User $user;
    private Article $article;
    private \DateTime $createdAt;

    public function __construct(string $id, int $rating, User $user, Article $article, ?string $comment = null)
    {
        $this->id = $id;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->user = $user;
        $this->article = $article;
        $this->createdAt = new \DateTime();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getArticle(): Article
    {
        return $this->article;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setRating(int $rating): void
    {
        $this->rating = $rating;
    }

    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }
}

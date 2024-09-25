<?php

namespace App\Main\Domain\Repository\Interfaces\Article;

use App\Main\Domain\Model\Article;

interface IArticleRepository
{
    /**
     * @return Article[]
     */
    public function findAll(): array;

    public function save(Article $article): void;

    public function update(Article $article): void;

    public function delete(string $id): void;

    public function find(string $id): ?Article;

    public function findById(string $id): ?Article;
    public function findAllByUserId(string $userId): array;
    public function findByUserIdAndArticleId(string $userId, string $articleId): ?Article;
    public function findAllByUserAndFavorites(string $userId): array;
    public function findAllAndFavorites(string $userId): array;
}

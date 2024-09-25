<?php

namespace App\Main\Domain\Repository\Interfaces\Favorite;

use App\Main\Domain\Model\Favorite;
use App\Main\Domain\Model\User;
use App\Main\Domain\Model\Article;

interface IFavoriteRepository
{
    /**
     * @param User $user
     * @param Article $article
     * @return Favorite|null
     */
    public function findByUserAndArticle(User $user, Article $article): ?Favorite;

    /**
     * @return Favorite[]
     */
    public function findAll(): array;

    /**
     * @param Favorite $favorite
     */
    public function save(Favorite $favorite): void;

    /**
     * @param string $id
     * @return Favorite|null
     */
    public function find(string $id): ?Favorite;

    /**
     * @param Favorite $favorite
     */
    public function delete(Favorite $favorite): void;

    public function findAllFavoritesByUserId(string $userId): array;

}

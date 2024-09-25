<?php

namespace App\Main\Infrastructure\Repository\Favorite;

use App\Main\Domain\Model\Favorite;
use App\Main\Domain\Model\User;
use App\Main\Domain\Model\Article;
use App\Main\Domain\Repository\Interfaces\Favorite\IFavoriteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class FavoriteRepository implements IFavoriteRepository
{
    private EntityRepository $repository;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        $this->repository = $this->entityManager->getRepository(Favorite::class);
    }

    /**
     * @return Favorite[]
     */
    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    public function save(Favorite $favorite): void
    {
        $this->entityManager->persist($favorite);
        $this->entityManager->flush();
    }

    public function delete(Favorite $favorite): void
    {
        $this->entityManager->remove($favorite);
        $this->entityManager->flush();
    }

    public function find(string $id): ?Favorite
    {
        return $this->repository->find($id);
    }

    /**
     *
     * @param User $user
     * @param Article $article
     * @return Favorite|null
     */
    public function findByUserAndArticle(User $user, Article $article): ?Favorite
    {
        return $this->repository->findOneBy([
            'user' => $user,
            'article' => $article,
        ]);
    }

    /**
     *
     * @param string $userId
     * @return Favorite[]
     */
    public function findAllFavoritesByUserId(string $userId): array
    {
        $query = $this->entityManager->createQuery(
            'SELECT f FROM App\Main\Domain\Model\Favorite f
             JOIN f.user u
             WHERE u.id = :userId'
        )->setParameter('userId', $userId);

        return $query->getResult();
    }
}

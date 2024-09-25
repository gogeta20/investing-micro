<?php

namespace App\Main\Infrastructure\Repository\Article;

use App\Main\Domain\Model\Article;
use App\Main\Domain\Repository\Interfaces\Article\IArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class ArticleRepository implements IArticleRepository
{
    private EntityRepository $repository;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        $this->repository = $this->entityManager->getRepository(Article::class);
    }

    /**
     * @return Article[]
     */
    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    public function save(Article $article): void
    {
        $this->entityManager->persist($article);
        $this->entityManager->flush();
    }

    public function update(Article $article): void
    {
        $this->entityManager->persist($article);
        $this->entityManager->flush();
    }

    public function delete(string $id): void
    {
        $article = $this->find($id);
        if ($article !== null) {
            $this->entityManager->remove($article);
            $this->entityManager->flush();
        }
    }

    public function find(string $id): ?Article
    {
        return $this->repository->find($id);
    }

    public function findById(string $id): ?Article
    {
        return $this->repository->find($id);
    }

    /**
     * Busca todos los artículos de un usuario por su UUID
     *
     * @param string $userId
     * @return Article[]
     */
    public function findAllByUserId(string $userId): array
    {
        return $this->repository->findBy(['author' => $userId]);
    }

    /**
     * Busca un artículo específico de un usuario por el UUID del autor y el UUID del artículo
     *
     * @param string $userId
     * @param string $articleId
     * @return Article|null
     */
    public function findByUserIdAndArticleId(string $userId, string $articleId): ?Article
    {
        return $this->repository->findOneBy([
            'author' => $userId,
            'id' => $articleId
        ]);
    }

    /**
     * Devuelve todos los artículos creados por el usuario o marcados como favoritos por el usuario.
     *
     * @param string $userId
     * @return Article[]
     */
    public function findAllByUserAndFavorites(string $userId): array
    {
        $query = $this->entityManager->createQuery(
            'SELECT a.id, a.title, a.body, a.id, a.mediaUrl, u.id as author, u.email,
                CASE
                    WHEN f.user IS NOT NULL THEN true
                    ELSE false
                END AS isFavorite
        FROM App\Main\Domain\Model\Article a
        LEFT JOIN App\Main\Domain\Model\User u WITH a.author = u.id
        LEFT JOIN App\Main\Domain\Model\Favorite f WITH a.id = f.article AND f.user = :userId
        WHERE a.author = :userId OR f.user = :userId'
        );
        $query->setParameter('userId', $userId);

        return $query->getResult();
    }

    /**
     * Devuelve todos los artículos creados por el usuario o marcados como favoritos por el usuario.
     *
     * @param string $userId
     * @return Article[]
     */
    public function findAllAndFavorites(string $userId): array
    {
        $query = $this->entityManager->createQuery(
            'SELECT a.id, a.title, a.body, a.mediaUrl, u.id as author, u.email,
            CASE
                WHEN f.user IS NOT NULL THEN true
                ELSE false
            END AS isFavorite
        FROM App\Main\Domain\Model\Article a
        LEFT JOIN App\Main\Domain\Model\User u WITH a.author = u.id
        LEFT JOIN App\Main\Domain\Model\Favorite f WITH f.article = a.id AND f.user = :userId'
        );
        $query->setParameter('userId', $userId);

        return $query->getResult();
    }
}

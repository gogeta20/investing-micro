<?php
namespace App\Main\Infrastructure\Repository\Rating;

use App\Main\Domain\Model\Rating;
use App\Main\Domain\Repository\Interfaces\Rating\IRatingRepository;
use Doctrine\ORM\EntityManagerInterface;

class RatingRepository implements IRatingRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findAll(): array
    {
        return $this->entityManager->getRepository(Rating::class)->findAll();
    }

    public function save(Rating $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function update(Rating $user): void
    {
        $this->entityManager->merge($user);
        $this->entityManager->flush();
    }

    public function delete(string $id): void
    {
        $user = $this->find($id);
        if ($user) {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
        }
    }

    public function find(string $id): ?Rating
    {
        return $this->entityManager->getRepository(Rating::class)->find($id);
    }

    public function findByEmail(string $email): ?Rating
    {
        return $this->entityManager->getRepository(Rating::class)->findOneBy(['email' => $email]);
    }
}

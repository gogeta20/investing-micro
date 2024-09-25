<?php
namespace App\Main\Infrastructure\Repository\User;

use App\Main\Domain\Model\User;
use App\Main\Domain\Repository\Interfaces\User\IUserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserRepository implements IUserRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findAll(): array
    {
        return $this->entityManager->getRepository(User::class)->findAll();
    }

    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function update(User $user): void
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

    public function find(string $id): ?User
    {
        return $this->entityManager->getRepository(User::class)->find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
    }
}

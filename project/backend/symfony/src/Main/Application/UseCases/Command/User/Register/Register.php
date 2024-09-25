<?php

namespace App\Main\Application\UseCases\Command\User\Register;

use App\Main\Domain\Exception\StoreException;
use App\Main\Domain\Model\User;
use App\Main\Domain\Repository\Interfaces\User\IUserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Ramsey\Uuid\Uuid;

readonly class Register
{
    public function __construct(
        private IUserRepository $repository,
        private UserPasswordHasherInterface $passwordHash
    ) {}

    /**
     * @throws StoreException
     */
    public function __invoke(RegisterCommand $command): array
    {
        try {
            $existingUser = $this->repository->findByEmail($command->email());
            if ($existingUser !== null) {
                throw new StoreException(sprintf('El email %s ya está en uso.', $command->email()));
            }

            $user = new User(
                Uuid::uuid4()->toString(),
                "",
                $command->email(),
                $this->passwordHash->hashPassword(new User('','', $command->email(), ''), $command->password()), // Hashear la contraseña
                ['ROLE_USER']
            );

            $this->repository->save($user);
            return [];
        } catch (\Exception $e) {
            throw new StoreException('No se puede registrar el usuario por el motivo -> ' . $e->getMessage());
        }
    }
}

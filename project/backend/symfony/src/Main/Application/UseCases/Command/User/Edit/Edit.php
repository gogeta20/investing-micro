<?php
declare(strict_types=1);

namespace App\Main\Application\UseCases\Command\User\Edit;

use App\Main\Domain\Exception\StoreException;
use App\Main\Domain\Repository\Interfaces\User\IUserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class Edit
{
    public function __construct(
        private IUserRepository $repository,
        private JWTTokenManagerInterface $jwtTokenManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    /**
     * @throws StoreException
     */
    public function __invoke(EditUserCommand $command): array
    {
        try {
            $user = $this->repository->findByEmail($command->email());
            if ($user === null) {
                throw new StoreException('Usuario no encontrado.');
            }

            if ($command->username() !== null) {
                $user->setName($command->username());
            }

            if ($command->password() !== null) {
                $hashedPassword = $this->passwordHasher->hashPassword($user, $command->password());
                $user->setPassword($hashedPassword);
            }

            if ($command->newEmail() !== null) {
                $user->setEmail($command->newEmail());
                $token = $this->jwtTokenManager->create($user) ?? '';
                $user->setToken($token);
            }

            $this->repository->save($user);

            return ['user' => $user];
        } catch (\Exception $e) {
            throw new StoreException('Error al actualizar el perfil: ' . $e->getMessage());
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Querys\User\ById;

use App\Main\Domain\Exception\StoreException;
use App\Main\Domain\Repository\Interfaces\User\IUserRepository;

final readonly class GetUserById
{
    public function __construct(private IUserRepository $repository)
    {}

    /**
     * @throws StoreException
     */
    public function __invoke(string $user): array
    {
        try {
            $user = $this->repository->findByEmail($user);

            if ($user === null) {
                throw new StoreException('Usuario no encontrado.');
            }

            return $user->toArray();
        } catch (\Exception $e) {
            throw new StoreException('Error al obtener el usuario del repositorio: ' . $e->getMessage());
        }
    }
}

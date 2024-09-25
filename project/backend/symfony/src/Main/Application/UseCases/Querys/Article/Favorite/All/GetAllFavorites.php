<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Querys\Article\Favorite\All;

use App\Main\Domain\Exception\StoreException;
use App\Main\Domain\Repository\Interfaces\Favorite\IFavoriteRepository;
use App\Main\Domain\Repository\Interfaces\User\IUserRepository;

readonly class GetAllFavorites
{
    public function __construct(
        private IFavoriteRepository $favoriteRepository,
        private IUserRepository $userRepository,
    ) {}

    /**
     * @throws StoreException
     */
    public function __invoke(string $email): array
    {
        try {
            $user = $this->userRepository->findByEmail($email);
            $favorites = $this->favoriteRepository->findAllFavoritesByUserId($user->getId());
            return array_map(fn($favorite) => $favorite->toArray(), $favorites);
        } catch (\Exception $e) {
            throw new StoreException('Error al obtener los favoritos del usuario: ' . $e->getMessage());
        }
    }
}

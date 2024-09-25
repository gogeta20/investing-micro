<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Command\Article\Favorite\Create;

use App\Main\Domain\Model\Favorite;
use App\Main\Domain\Repository\Interfaces\Favorite\IFavoriteRepository;
use App\Main\Domain\Model\User;
use App\Main\Domain\Model\Article;
use App\Main\Domain\Exception\StoreException;
use Ramsey\Uuid\Uuid;

readonly class CreateFavorite
{
    public function __construct(
        private IFavoriteRepository $favoriteRepository
    ) {}

    /**
     * @throws StoreException
     */
    public function __invoke(User $user, Article $article): array
    {
        try {
            $existingFavorite = $this->favoriteRepository->findByUserAndArticle($user, $article);
            if ($existingFavorite) {
                $this->favoriteRepository->delete($existingFavorite);
                return ['message' => 'El artículo ha sido eliminado de la lista de favoritos.'];
            }

            $favorite = new Favorite(
                Uuid::uuid4()->toString(),
                $user,
                $article
            );

            $this->favoriteRepository->save($favorite);

            return ['favorite' => $favorite];
        } catch (\Exception $e) {
            throw new StoreException('Error al agregar el favorito: ' . $e->getMessage());
        }
    }
}

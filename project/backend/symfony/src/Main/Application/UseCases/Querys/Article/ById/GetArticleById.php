<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Querys\Article\ById;

use App\Main\Domain\Exception\StoreException;
use App\Main\Domain\Repository\Interfaces\Article\IArticleRepository;

final readonly class GetArticleById
{
    public function __construct(private IArticleRepository $repository)
    {}

    /**
     * @throws StoreException
     */
    public function __invoke(string $user): array
    {
        try {
            $article = $this->repository->findById($user);

            if ($article === null) {
                throw new StoreException('Usuario no encontrado.');
            }

            return $article->toArray();
        } catch (\Exception $e) {
            throw new StoreException('Error al obtener el usuario del repositorio: ' . $e->getMessage());
        }
    }
}

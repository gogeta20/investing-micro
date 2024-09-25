<?php

namespace App\Main\Application\UseCases\Querys\Article\All;

use App\Main\Domain\Exception\StoreException;
use App\Main\Domain\Repository\Interfaces\Article\IArticleRepository;
use App\Main\Domain\Repository\Interfaces\User\IUserRepository;

final readonly class GetAllArticles
{
    public function __construct(
        private IUserRepository $repository,
        private IArticleRepository $articleRepository
    )
    {}

    /**
     * @throws StoreException
     */
    public function __invoke(GetAllArticlesQuery $query): array
    {
        try {
            $user = $this->repository->findByEmail($query->userId());
            if ($query->all()){
                return  $this->articleRepository->findAllAndFavorites($user->getId());
            }else{
                return $this->articleRepository->findAllByUserAndFavorites($user->getId());
            }
        } catch (\Exception $e) {
            throw new StoreException('Error fetching articles from repository: ' . $e->getMessage());
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Command\Article\Create;

use App\Main\Domain\Exception\StoreException;
use App\Main\Domain\Model\Article;
use App\Main\Domain\Repository\Interfaces\Article\IArticleRepository;
use App\Main\Domain\Repository\Interfaces\User\IUserRepository;
use Ramsey\Uuid\Uuid;

readonly class CreateArticle
{
    public function __construct(
        private readonly IUserRepository   $userRepository,
        private IArticleRepository $repository
    ) {}

    /**
     * @throws StoreException
     */
    public function __invoke(CreateArticleCommand $command): array
    {
        try {
            $user = $this->userRepository->findByEmail($command->email());
            $article = new Article(
                Uuid::uuid4()->toString(),
                $command->title(),
                $command->body(),
                $command->mediaUrl(),
                $command->mediaType(),
                $user
            );

            $this->repository->save($article);

            return ['article' => $article];
        } catch (\Exception $e) {
            throw new StoreException('Error al crear el artículo: ' . $e->getMessage());
        }
    }
}

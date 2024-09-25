<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Command\Article\UpdateArticle;

use App\Main\Domain\Exception\StoreException;
use App\Main\Domain\Model\Article;
use App\Main\Domain\Repository\Interfaces\Article\IArticleRepository;
use App\Main\Domain\Repository\Interfaces\User\IUserRepository;

final readonly class UpdateArticle
{
    public function __construct(
        private IUserRepository $userRepository,
        private IArticleRepository $repository
    ) {}

    /**
     * @throws StoreException
     */
    public function __invoke(UpdateArticleCommand $command): array
    {
        try {
            $user = $this->userRepository->findByEmail($command->email());
            $article = $this->repository->findByUserIdAndArticleId($user->getId(),$command->id());

            if ($article === null) {
                throw new StoreException('Artículo no encontrado.');
            }

            if ($command->title() !== null) {
                $article->setTitle($command->title());
            }

            if ($command->body() !== null) {
                $article->setBody($command->body());
            }

            if ($command->mediaUrl() !== null) {
                $article->setMediaUrl($command->mediaUrl());
                $article->setMediaType($command->mediaType());
            }

            $this->repository->save($article);

            return ['article' => $article];
        } catch (\Exception $e) {
            throw new StoreException('Error al actualizar el artículo: ' . $e->getMessage());
        }
    }
}

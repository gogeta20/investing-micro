<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Command\Article\DeleteArticle;

use App\Main\Domain\Exception\StoreException;
use App\Main\Domain\Repository\Interfaces\Article\IArticleRepository;

final class DeleteArticle
{
    public function __construct(private IArticleRepository $articleRepository) {

    }

    public function __invoke(DeleteArticleCommand $command): void
    {
        try {
            $article = $this->articleRepository->findById($command->id());

            if ($article === null) {
                throw new StoreException('Artículo no encontrado.');
            }

            $this->articleRepository->delete($command->id());
        } catch (\Exception $e) {
            throw new StoreException('Error al eliminar el artículo: ' . $e->getMessage());
        }
    }
}

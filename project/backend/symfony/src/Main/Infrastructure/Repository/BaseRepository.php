<?php

namespace App\Main\Infrastructure\Repository\Article;

use App\Main\Domain\Model\Article;
use App\Main\Domain\Repository\Interfaces\IBaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class BaseRepository implements IBaseRepository
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    )
    {
    }

    public function repository(string $entityClass): EntityRepository
    {
        return $this->entityManager->getRepository($entityClass);
    }

}
